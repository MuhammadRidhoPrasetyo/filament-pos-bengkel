<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Purchases\PurchaseResource;
use App\Models\ProductMovement;
use App\Models\Purchase;
use App\Models\PurchaseItem;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;
    protected ?bool $hasDatabaseTransactions = true;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            $purchase = $this->record;
            $purchaseItems = $purchase->items;
            $purchaseItems->each(function ($item) use ($purchase) {
                 ProductMovement::create([
                    'product_id' => $item['product_id'],
                    'store_id' => $purchase->store_id,
                    'movement_type' => 'in',
                    'quantity' => $item['quantity_ordered'],
                    'movementable_id' => $item['id'],
                    'movementable_type' => PurchaseItem::class,
                    'occurred_at' => now(),
                    'created_by' => auth()->id(),
                    'note' => $item['note']
                ]);

                $productStock = ProductStock::query()
                    ->where('product_id', $item['product_id'])
                    ->where('store_id', $purchase->store_id)
                    ->first();
                $productStock->increment('quantity', $item['quantity_ordered']);
            });
        });
    }
}
