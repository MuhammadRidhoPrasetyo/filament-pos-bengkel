<?php

namespace App\Filament\Clusters\Transactions\Resources\Transactions\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\Transactions\Resources\Transactions\TransactionResource;


class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Pastikan items sudah ke-load
        $this->record->load('items');

        $items = $this->record->items;

        DB::transaction(function () use ($items) {
            $subtotal          = 0;
            $itemDiscountTotal = 0;
            $totalCost         = 0;
            $totalProfit       = 0;

            foreach ($items as $item) {
                $qty        = (int)   $item->quantity;
                $unitPrice  = (float) $item->unit_price;
                $discMode   =        $item->item_discount_mode;
                $discValue  = (float) $item->item_discount_value;
                $unitCost   = (float) $item->unit_cost;

                $lineSubtotal = $qty * $unitPrice;

                if (! $discMode || $discValue <= 0) {
                    $discAmount = 0;
                } elseif ($discMode === 'percent') {
                    $discAmount = $lineSubtotal * ($discValue / 100);
                } else {
                    $discAmount = $discValue;
                }

                $finalUnitPrice = $qty > 0
                    ? ($lineSubtotal - $discAmount) / $qty
                    : $unitPrice;

                $lineTotal     = $qty * $finalUnitPrice;
                $lineCostTotal = $qty * $unitCost;
                $lineProfit    = $lineTotal - $lineCostTotal;

                $subtotal          += $lineSubtotal;
                $itemDiscountTotal += $discAmount;
                $totalCost         += $lineCostTotal;
                $totalProfit       += $lineProfit;
            }

            $subtotalAfterItemDiscount = $subtotal - $itemDiscountTotal;

            $universalMode  = $this->record->universal_discount_mode;
            $universalValue = (float) $this->record->universal_discount_value;

            if (! $universalMode || $universalValue <= 0) {
                $universalAmount = 0;
            } elseif ($universalMode === 'percent') {
                $universalAmount = $subtotalAfterItemDiscount * ($universalValue / 100);
            } else {
                $universalAmount = $universalValue;
            }

            $taxTotal = (float) $this->record->tax_total;

            $grandTotal = max($subtotalAfterItemDiscount - $universalAmount + $taxTotal, 0);

            $paidAmount   = (float) $this->record->paid_amount;
            $changeAmount = max($paidAmount - $grandTotal, 0);

            $this->record->update([
                'subtotal'                     => round($subtotal, 2),
                'item_discount_total'          => round($itemDiscountTotal, 2),
                'subtotal_after_item_discount' => round($subtotalAfterItemDiscount, 2),
                'universal_discount_amount'    => round($universalAmount, 2),
                'tax_total'                    => round($taxTotal, 2),
                'grand_total'                  => round($grandTotal, 2),
                'change_amount'                => round($changeAmount, 2),
                'total_cost'                   => round($totalCost, 2),
                'total_profit'                 => round($totalProfit, 2),
            ]);
        });
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // ============================
        // NORMALISASI INPUT HEADER
        // ============================

        // Mode diskon universal boleh null
        $data['universal_discount_mode'] = $data['universal_discount_mode'] ?: null;

        // Pastikan angka-angka yang diketik user selalu ada default
        $data['universal_discount_value'] = (float) ($data['universal_discount_value'] ?? 0);
        $data['tax_total']                = (float) ($data['tax_total'] ?? 0);
        $data['paid_amount']              = (float) ($data['paid_amount'] ?? 0);

        // ============================
        // HAPUS FIELD YANG DIHITUNG OTOMATIS
        // (BIAR TIDAK DIOVERWRITE DENGAN 0 DARI FORM)
        // ============================

        unset(
            $data['subtotal'],
            $data['item_discount_total'],
            $data['subtotal_after_item_discount'],
            $data['universal_discount_amount'],
            $data['grand_total'],
            $data['change_amount'],
            $data['total_cost'],
            $data['total_profit'],
        );

        return $data;
    }
}
