<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Brand;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use App\Models\ProductCategory;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(
            //     fn(Builder $query) => $query
            //         ->when(!Auth::user()->hasRole('owner'), function ($query) {
            //             return $query->where('store_id', Auth::user()->store_id);
            //         })
            // )
            ->columns([
                TextColumn::make('productCategory.name')
                    ->label('Kategori Produk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Merk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe Produk')
                    ->searchable(),
                TextColumn::make('keyword')
                    ->label('Kata Kunci')
                    ->searchable(),
                TextColumn::make('compatibility')
                    ->label('Kompatibilitas')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Ukuran')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->hidden()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->hidden()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product_category_id')
                    ->options(ProductCategory::all()->pluck('name', 'id')),

                SelectFilter::make('brand_id')
                    ->options(Brand::all()->pluck('name', 'id')),
            ])
            ->recordActions([
                // Label Setting: modal to create/update product_label relation
                Action::make('labelSetting')
                    ->label('Label Setting')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Toggle::make('label_brand')
                            ->label('Tampilkan Merk')
                            ->helperText('Tampilkan merk pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_brand ?? false)),

                        Toggle::make('label_sku')
                            ->label('Tampilkan SKU')
                            ->helperText('Tampilkan SKU pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_sku ?? false)),

                        Toggle::make('label_category')
                            ->label('Tampilkan Kategori')
                            ->helperText('Tampilkan kategori produk pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_category ?? false)),

                        Toggle::make('label_type')
                            ->label('Tampilkan Tipe')
                            ->helperText('Tampilkan tipe produk pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_type ?? false)),

                        Toggle::make('label_size')
                            ->label('Tampilkan Ukuran')
                            ->helperText('Tampilkan ukuran produk pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_size ?? false)),

                        Toggle::make('label_unit')
                            ->label('Tampilkan Satuan')
                            ->helperText('Tampilkan satuan pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_unit ?? false)),

                        Toggle::make('label_keyword')
                            ->label('Tampilkan Kata Kunci')
                            ->helperText('Tampilkan kata kunci pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_keyword ?? false)),

                        Toggle::make('label_compatibility')
                            ->label('Tampilkan Kompatibilitas')
                            ->helperText('Tampilkan kompatibilitas pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_compatibility ?? false)),

                        Toggle::make('label_description')
                            ->label('Tampilkan Deskripsi (dipangkas)')
                            ->helperText('Tampilkan potongan deskripsi pada label')
                            ->default(fn ($record) => (bool) ($record->productLabel?->label_description ?? false)),

                        TextInput::make('separator')
                            ->label('Separator')
                            ->helperText('Optional - tanda pemisah antar elemen pada label, gunakan tanda - atau |')
                            ->default(fn ($record) => $record->productLabel?->separator ?? null),

                        Placeholder::make('preview')
                            ->label('Preview Label')
                            ->content(fn (callable $get, $record) =>
                                (function () use ($get, $record) {
                                    $sep = $get('separator') ?? $record->productLabel?->separator ?? ' - ';
                                    $parts = [];

                                    if (($get('label_sku') ?? $record->productLabel?->label_sku ?? false) && $record->sku) {
                                        $parts[] = $record->sku;
                                    }
                                    if (($get('label_brand') ?? $record->productLabel?->label_brand ?? false) && $record->brand?->name) {
                                        $parts[] = $record->brand->name;
                                    }
                                    if (($get('label_category') ?? $record->productLabel?->label_category ?? false) && $record->productCategory?->name) {
                                        $parts[] = $record->productCategory->name;
                                    }
                                    if (($get('label_type') ?? $record->productLabel?->label_type ?? false) && $record->type) {
                                        $parts[] = $record->type;
                                    }
                                    if (($get('label_size') ?? $record->productLabel?->label_size ?? false) && $record->size) {
                                        $parts[] = $record->size;
                                    }
                                    if (($get('label_unit') ?? $record->productLabel?->label_unit ?? false)) {
                                        $unitName = $record->unit?->name ?? (is_string($record->unit) ? $record->unit : null);
                                        if ($unitName) {
                                            $parts[] = $unitName;
                                        }
                                    }
                                    if (($get('label_keyword') ?? $record->productLabel?->label_keyword ?? false) && $record->keyword) {
                                        $parts[] = $record->keyword;
                                    }
                                    if (($get('label_compatibility') ?? $record->productLabel?->label_compatibility ?? false) && $record->compatibility) {
                                        $parts[] = $record->compatibility;
                                    }
                                    if (($get('label_description') ?? $record->productLabel?->label_description ?? false) && $record->description) {
                                        $parts[] = Str::limit(strip_tags($record->description), 60);
                                    }

                                    $label = implode($sep, array_filter($parts));


                                    return  e($label) ;
                                })()
                            ),
                    ])
                    ->modalHeading('Label Setting')
                    ->modalWidth('md')
                    ->action(function ($record, array $data) {
                        // ensure product_category_id and brand_id are set from product
                        $values = array_merge([
                            'product_category_id' => $record->product_category_id,
                            'brand_id' => $record->brand_id,
                        ], $data);

                        $pl = $record->productLabel()->first();
                        if ($pl) {
                            $pl->update($values);
                        } else {
                            $values['product_id'] = $record->id;
                            $record->productLabel()->create($values);
                        }

                        Notification::make()->success()->title('Label disimpan')->send();
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
