<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Dom\Text;
use App\Models\Store;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    // protected static ?string $relatedResource = ProductResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query
                    ->when(!Auth::user()->hasRole('owner'), function ($query) {
                        return $query->where('store_id', Auth::user()->store_id);
                    })
            )
            ->columns([
                TextColumn::make('store.name')
                    ->label('Bengkel')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->searchable(),
                TextColumn::make('product.unit.name')
                    ->label('Satuan'),
                TextColumn::make('productPrice.purchase_price')
                    ->label('Harga Beli')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('productPrice.markup')
                    ->label('Markup')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('productPrice.selling_price')
                    ->label('Harga Produk')
                    ->searchable(),

            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        Select::make('store_id')
                            ->label('Toko')
                            ->columnSpanFull()
                            ->options(
                                Store::query()
                                    ->when(!Auth::user()->hasRole('owner'), function ($query) {
                                        return $query->where('id', Auth::user()->store_id);
                                    })
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->distinct(),

                        TextInput::make('quantity')
                            ->label('Jumlah')
                            ->integer(),

                        TextInput::make('minimum_stock')
                            ->label('Stok Minimum')
                            ->integer(),
                    ]),
            ]);
    }
}
