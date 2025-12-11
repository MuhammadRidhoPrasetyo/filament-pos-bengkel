<?php

namespace App\Filament\Clusters\Reports\Resources\TransactionItems\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('transaction_id'),
                TextEntry::make('product_id'),
                TextEntry::make('store_id'),
                TextEntry::make('product_stock_id')
                    ->placeholder('-'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('unit_price')
                    ->money(),
                TextEntry::make('item_discount_mode')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('item_discount_value')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('item_discount_amount')
                    ->numeric(),
                TextEntry::make('final_unit_price')
                    ->money(),
                TextEntry::make('line_subtotal')
                    ->numeric(),
                TextEntry::make('line_total')
                    ->numeric(),
                TextEntry::make('discount_type_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('unit_cost')
                    ->money(),
                TextEntry::make('line_cost_total')
                    ->numeric(),
                TextEntry::make('line_profit')
                    ->numeric(),
                IconEntry::make('price_edited')
                    ->boolean(),
                TextEntry::make('pricing_mode')
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
