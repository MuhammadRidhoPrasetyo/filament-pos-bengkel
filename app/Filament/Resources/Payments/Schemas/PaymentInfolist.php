<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class PaymentInfolist
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
                                TextEntry::make('name')
                                    ->label('Metode Pembayaran')
                                    ->columnSpanFull(),
                                TextEntry::make('type')
                                    ->label('Tipe')
                                    ->columnSpanFull(),
                                TextEntry::make('account_number')
                                    ->label('Nomor Pembayaran')
                                    ->columnSpanFull(),
                                TextEntry::make('account_name')
                                    ->label('Atas Nama')
                                    ->columnSpanFull(),
                                TextEntry::make('provider_code')
                                    ->label('Kode Provider')
                                    ->columnSpanFull(),
                            ]),


                        Section::make()
                            ->columns(4)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 4,
                                'xl' => 4,
                            ])->schema([
                                SpatieMediaLibraryImageEntry::make('logo')
                                    ->collection('payment_logo')
                            ]),

                    ])

            ]);
    }
}
