<?php

namespace App\Filament\Resources\ProductStocks\Pages;

use App\Models\ProductStock;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\ProductStocks\ProductStockResource;
use Illuminate\Database\Eloquent\Builder;

class ListProductStocks extends ListRecords
{
    protected static string $resource = ProductStockResource::class;

    public function getTabs(): array
    {
        $nearPct = 0.25;

        return [
            'Semua Barang' => Tab::make()
                ->badge(fn() => ProductStock::count()),

            'Mendekati Min' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) use ($nearPct) {
                    $query
                        ->whereNotNull('minimum_stock')
                        ->where('minimum_stock', '>', 0)
                        // masih di atas minimum
                        ->whereColumn('quantity', '>', 'minimum_stock')
                        // tapi sudah mendekati (<= minimum + 25%)
                        ->whereRaw('quantity <= minimum_stock + (minimum_stock * ?)', [$nearPct]);
                })
                ->badge(
                    fn() => ProductStock::query()
                        ->whereNotNull('minimum_stock')
                        ->where('minimum_stock', '>', 0)
                        ->whereColumn('quantity', '>', 'minimum_stock')
                        ->whereRaw('quantity <= minimum_stock + (minimum_stock * ?)', [$nearPct])
                        ->count()
                ),


            'Di Bawah Min' => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query
                        ->whereNotNull('minimum_stock')
                        ->where('minimum_stock', '>', 0)
                        ->where('quantity', '>', 0)
                        ->whereColumn('quantity', '<', 'minimum_stock');
                })
                ->badge(
                    fn() => ProductStock::query()
                        ->whereNotNull('minimum_stock')
                        ->where('minimum_stock', '>', 0)
                        ->where('quantity', '>', 0)
                        ->whereColumn('quantity', '<', 'minimum_stock')
                        ->count()
                ),

            'Habis (0)' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('quantity', 0))
                ->badge(fn() => ProductStock::query()->where('quantity', 0)->count()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
