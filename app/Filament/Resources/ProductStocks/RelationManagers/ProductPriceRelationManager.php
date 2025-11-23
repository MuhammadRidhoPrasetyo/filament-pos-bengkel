<?php

namespace App\Filament\Resources\ProductStocks\RelationManagers;

use Filament\Tables\Table;
use App\Models\ProductPrice;
use App\Models\ProductStock;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ProductStocks\ProductStockResource;
use Filament\Schemas\Components\Utilities\Get;

class ProductPriceRelationManager extends RelationManager
{
    protected static string $relationship = 'productPrices';

    // protected static ?string $relatedResource = ProductStockResource::class;
    protected static ?string $title = 'Daftar Harga';
    protected static ?string $pluralLabel = 'Daftar Harga';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('price_type')
                ->label('Tipe Harga')
                ->options([
                    'toko' => 'Toko',
                    'distributor' => 'Distributor',
                ])
                ->required(),
            TextInput::make('purchase_price')
                ->label('Harga Beli')
                ->required()
                ->numeric()
                ->live(),

            TextInput::make('markup')
                ->label('Markup')
                ->required()
                ->numeric()
                ->live(),

            TextInput::make('selling_price')
                ->label('Harga Jual')
                ->required()
                ->numeric()
                ->disabled() // agar user tidak bisa edit manual (opsional)
                ->formatStateUsing(function ($state, Get $get, Set $set) {
                    return $get('purchase_price') + $get('markup');
                }),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Harga')
            ->columns([
                ToggleColumn::make('is_active')
                    ->beforeStateUpdated(function ($record, $state) {
                        ProductPrice::query()
                            ->where('product_id', $record->product_id)
                            ->where('store_id', $record->store_id)
                            ->where('is_active', true)
                            ->where('id', '!=', $record->id)
                            ->update(['is_active' => false]);
                    })
                    ->afterStateUpdated(function ($record, $state) {
                        $productStock = ProductStock::query()
                            ->where('product_id', $record->product_id)
                            ->where('store_id', $record->store_id)
                            ->first();

                        $productStock->update(['product_price_id' => $record->id]);
                        $productStock->productPriceHistories()->create([
                            'product_price_id' => $record->id,
                            'store_id' => $record->store_id,
                            'prodcut_id' => $record->product_id,
                            'date' => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Harga Berhasil Diperbarui!')
                            ->send();
                    })
                    ->label('Status Harga')
                    ->searchable(),
                TextColumn::make('price_type')
                    ->label('Tipe Harga')
                    ->searchable(),
                TextColumn::make('purchase_price')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->label('Harga Beli')
                    ->searchable(),
                TextColumn::make('markup')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->label('Markup')
                    ->searchable(),
                TextColumn::make('selling_price')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->label('Harga Jual')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['store_id'] = $this->getOwnerRecord()->store_id;
                        $data['product_id'] = $this->getOwnerRecord()->product_id;

                        return $data;
                    })
                    ->action(function (array $data) {
                        $productPrice = ProductPrice::create([
                            'product_id'     => $data['product_id'],
                            'store_id'       => $data['store_id'],
                            'price_type'     => $data['price_type'],
                            'purchase_price' => $data['purchase_price'],
                            'markup'         => $data['markup'],
                            'selling_price'  => $data['selling_price'],
                        ]);

                        $this->getOwnerRecord()
                            ->update([
                                'product_price_id' => $productPrice->id,
                            ]);

                        $this->getOwnerRecord()->productPriceHistories()->create([
                            'product_price_id' => $productPrice->id,
                            'store_id'         => $data['store_id'],
                            'product_id'       => $data['product_id'],
                            'date'             => now(),
                        ]);
                    }),
            ]);
    }
}
