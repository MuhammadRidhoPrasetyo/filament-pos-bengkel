<?php

namespace App\Filament\Clusters\Reports\Resources\PurchaseItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PurchaseItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('purchase_id'),
                TextEntry::make('product_id'),
                TextEntry::make('price_type')
                    ->badge(),
                TextEntry::make('quantity_ordered')
                    ->numeric(),
                TextEntry::make('unit_purchase_price')
                    ->money(),
                TextEntry::make('item_discount_type')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('item_discount_value')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
