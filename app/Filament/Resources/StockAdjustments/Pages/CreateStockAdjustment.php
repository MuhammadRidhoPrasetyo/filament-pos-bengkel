<?php

namespace App\Filament\Resources\StockAdjustments\Pages;

use App\Models\ProductStock;
use App\Models\ProductMovement;
use Illuminate\Support\Facades\DB;
use App\Models\StockAdjustmentItem;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\StockAdjustments\StockAdjustmentResource;

class CreateStockAdjustment extends CreateRecord
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['posted_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            $stockAdjustment = $this->record;
            $stockAdjustmentItems = $stockAdjustment->items;
            $stockAdjustmentItems->each(function ($item) use ($stockAdjustment) {
                ProductMovement::create([
                    'product_id' => $item['product_id'],
                    'store_id' => $stockAdjustment->store_id,
                    'movement_type' => $item['adjustment_type'],
                    'quantity' => $item['quantity'],
                    'movementable_id' => $item['id'],
                    'movementable_type' => StockAdjustmentItem::class,
                    'occurred_at' => now(),
                    'created_by' => auth()->id(),
                    'note' => $item['note']
                ]);

                $productStock = ProductStock::query()
                    ->where('product_id', $item['product_id'])
                    ->where('store_id', $stockAdjustment->store_id)
                    ->first();

                if ($item['adjustment_type'] == 'out') {
                    $productStock->decrement('quantity', $item['quantity']);
                } else {
                    $productStock->increment('quantity', $item['quantity']);
                }
            });
        });
    }
}
