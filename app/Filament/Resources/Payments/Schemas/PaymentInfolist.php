<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

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

                        Section::make('Informasi Metode Pembayaran')
                            ->icon(LucideIcon::CreditCard)
                            ->description('Detail lengkap metode pembayaran yang tersedia untuk transaksi.')
                            ->columns(8)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 8,
                                'xl' => 8,
                            ])->schema([
                                TextEntry::make('name')
                                    ->label('Metode Pembayaran')
                                    ->columnSpanFull()
                                    ->weight('semibold'),
                                TextEntry::make('type')
                                    ->label('Tipe')
                                    ->columnSpanFull()
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('account_number')
                                    ->label('Nomor Pembayaran')
                                    ->columnSpanFull()
                                    ->copyable()
                                    ->copyMessage('Nomor pembayaran disalin.'),
                                TextEntry::make('account_name')
                                    ->label('Atas Nama')
                                    ->columnSpanFull(),
                                TextEntry::make('provider_code')
                                    ->label('Kode Provider')
                                    ->columnSpanFull()
                                    ->badge()
                                    ->color('success'),
                            ]),


                        Section::make('Logo & Branding')
                            ->icon(LucideIcon::Image)
                            ->description('Identifikasi visual untuk metode pembayaran ini.')
                            ->columns(4)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 12,
                                'lg' => 4,
                                'xl' => 4,
                            ])->schema([
                                SpatieMediaLibraryImageEntry::make('logo')
                                    ->label('Logo')
                                    ->collection('payment_logo')
                            ]),

                    ])

            ]);
    }
}
