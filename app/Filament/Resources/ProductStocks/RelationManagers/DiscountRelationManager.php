<?php

namespace App\Filament\Resources\ProductStocks\RelationManagers;

use Filament\Tables\Table;
use App\Models\DiscountType;
use Filament\Schemas\Schema;
use App\Models\ProductDiscount;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\ProductStocks\ProductStockResource;

class DiscountRelationManager extends RelationManager
{
    protected static string $relationship = 'discounts';  // Relasi 'product' di model ProductStock

    // protected static ?string $parentResource = ProductResource::class;
    // protected static ?string $relatedResource = ProductResource::class;
    protected static ?string $title = 'Diskon';
    protected static ?string $pluralLabel = 'Diskon';

    public function isReadOnly(): bool
{
    return false;
}

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('discount_type_id')
                ->label('Tipe Diskon')
                ->options(
                    DiscountType::all()->pluck('name', 'id')
                )
                ->required(),
            Select::make('type')
                ->label('Tipe Diskon')
                ->options(
                    [
                        'percent' => 'Persentase',
                        'amount' => 'Nominal',
                    ]
                )
                ->required(),
            TextInput::make('value')
                ->label('Nilai Diskon')
                ->required()
                ->numeric(),
        ]);
    }


    public function table(Table $table): Table
    {
        return $table
            ->heading('Diskon')
            ->columns([
                TextColumn::make('discountType.name')
                    ->label('Tipe Diskon')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe Harga')
                    ->searchable(),
                TextColumn::make('value')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->type === 'amount') {
                            return 'Rp ' . number_format($state, 0, ',', '.');
                        }

                        if ($record->type === 'percent') {
                            return $state . '%';
                        }

                        return $state;
                    })
                    ->label('Nilai Diskon')
                    ->searchable(),
            ])
            ->recordActions([
                // ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Diskon')
                    ->mutateDataUsing(function (array $data): array {
                        $data['store_id'] = $this->getOwnerRecord()->store_id;
                        $data['product_id'] = $this->getOwnerRecord()->product_id;

                        return $data;
                    })
                    ->action(function (array $data) {
                        ProductDiscount::create([
                            'store_id' => $data['store_id'],
                            'product_id' => $data['product_id'],
                            'discount_type_id' => $data['discount_type_id'],
                            'type' => $data['type'],
                            'value' => $data['value'],
                        ]);
                    }),
            ]);
    }
}
