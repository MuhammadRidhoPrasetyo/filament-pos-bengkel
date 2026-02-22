<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

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

                        Section::make('Informasi Metode Pembayaran')
                            ->icon(LucideIcon::CreditCard)
                            ->description('Rincian lengkap metode pembayaran: nama, tipe, nomor rekening, dan penyedia layanan.')
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
                                    ->required()
                                    ->placeholder('Contoh: Bank Mandiri, GCash, Dana'),
                                TextInput::make('type')
                                    ->label('Tipe')
                                    ->placeholder('Contoh: Bank Transfer, E-Wallet, QRIS')
                                    ->columnSpanFull()
                                    ->default(null),
                                TextInput::make('account_number')
                                    ->label('Nomor Pembayaran')
                                    ->columnSpanFull()
                                    ->default(null)
                                    ->helperText('Nomor rekening atau nomor wallet untuk metode pembayaran ini.'),
                                TextInput::make('account_name')
                                    ->label('Atas Nama')
                                    ->columnSpanFull()
                                    ->default(null)
                                    ->helperText('Nama pemilik rekening atau akun pembayaran.'),
                                TextInput::make('provider_code')
                                    ->label('Kode Provider')
                                    ->columnSpanFull()
                                    ->default(null)
                                    ->helperText('Kode unik untuk integrasi dengan sistem pembayaran pihak ketiga.'),
                            ]),


                        Section::make('Logo & Branding')
                            ->icon(LucideIcon::Image)
                            ->description('Unggah logo atau ikon untuk identifikasi visual metode pembayaran.')
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
                                    ->helperText('Format: JPG, PNG. Rekomendasi: 200x200px atau lebih.')
                            ]),

                    ])


            ]);
    }
}
