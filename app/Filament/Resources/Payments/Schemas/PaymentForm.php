<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([

                        Section::make()
                            ->columns(8)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 8,
                                'xl' => 8,
                            ])->schema([
                                TextInput::make('name')
                                    ->label('Metode Pembayaran')
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('type')
                                    ->label('Tipe')
                                    ->placeholder('Contoh: Bank Transfer, E-Wallet, QRIS')
                                    ->columnSpanFull()
                                    ->default(null),
                                TextInput::make('account_number')
                                    ->label('Nomor Pembayaran')
                                    ->columnSpanFull()
                                    ->default(null),
                                TextInput::make('account_name')
                                    ->label('Atas Nama')
                                    ->columnSpanFull()
                                    ->default(null),
                                TextInput::make('provider_code')
                                    ->label('Kode Provider')
                                    ->columnSpanFull()
                                    ->default(null),
                            ]),


                        Section::make()
                            ->columns(4)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 4,
                                'xl' => 4,
                            ])->schema([
                                SpatieMediaLibraryFileUpload::make('logo')
                                    ->label('Logo')
                                    ->columnSpanFull()
                                    ->collection('payment_logo')
                            ]),

                    ])


            ]);
    }
}
