<?php

namespace App\Filament\Resources\CashFlows\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CashFlowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Transaksi Kas')
                    ->description('Masukkan detail pemasukan atau pengeluaran kas')
                    ->icon('heroicon-m-banknotes')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('store_id')
                                    ->label('Toko / Bengkel')
                                    ->relationship('store', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->default(fn () => auth()->user()?->store_id)
                                    ->required(),

                                Select::make('category_id')
                                    ->label('Kategori Kas')
                                    ->relationship('category', 'name', fn ($query) => $query->where('is_active', true))
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->name} (" . ($record->type === 'income' ? 'Pemasukan' : 'Pengeluaran') . ")")
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            $category = \App\Models\CashFlowCategory::find($state);
                                            if ($category) {
                                                $set('type', $category->type);
                                            }
                                        }
                                    }),

                                DatePicker::make('date')
                                    ->label('Tanggal Transaksi')
                                    ->default(now())
                                    ->required()
                                    ->native(false),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->label('Nominal (Rp)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->required()
                                    ->default(0),

                                Select::make('user_id')
                                    ->label('Dicatat Oleh')
                                    ->relationship('user', 'name')
                                    ->default(fn () => auth()->id())
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ]),

                        // Hidden type field, auto-set from category
                        Select::make('type')
                            ->label('Jenis Arus Kas')
                            ->options([
                                'income' => 'Pemasukan',
                                'expense' => 'Pengeluaran',
                            ])
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->hidden(),

                        Textarea::make('description')
                            ->label('Keterangan / Deskripsi')
                            ->placeholder('Tuliskan rincian transaksi kas ini (opsional)...')
                            ->rows(3)
                            ->columnSpanFull()
                            ->default(null),
                    ])
                    ->columnSpanFull(),

                Section::make('Referensi Sistem (Otomatis)')
                    ->description('Hanya terisi jika kas ini digenerate otomatis dari sistem (misal: Transaksi Penjualan)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('reference_type')
                                    ->label('Tipe Referensi')
                                    ->disabled()
                                    ->default(null),

                                TextInput::make('reference_id')
                                    ->label('ID Referensi')
                                    ->disabled()
                                    ->default(null),
                            ])
                    ])
                    ->collapsed()
                    ->hiddenOn('create'),
            ]);
    }
}
