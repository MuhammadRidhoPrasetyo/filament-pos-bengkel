<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use Filament\Forms\Components\Select;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                        Section::make('Informasi Dasar')
                            ->icon(LucideIcon::Users)
                            ->description('Detail identitas supplier atau customer: kode unik, nama, dan tipe layanan.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('code')
                                    ->label('Kode Supplier')
                                    ->default(fn($livewire) => $livewire instanceof CreateSupplier
                                        ? Supplier::code()
                                        : null)
                                    ->readOnly()
                                    ->required(),
                                TextInput::make('name')
                                    ->label('Nama')
                                    ->required(),
                                Select::make('type')
                                    ->label('Tipe')
                                    ->options([
                                        'supplier' => 'Supplier',
                                        'customer' => 'Customer',
                                        'both' => 'Supplier & Customer',
                                    ]),
                                TextInput::make('contact_person')
                                    ->label('Kontak Person')
                                    ->default(null),
                            ]),

                        Section::make('Informasi Kontak')
                            ->icon(LucideIcon::Phone)
                            ->description('Nomor telepon, email, dan alamat lengkap untuk komunikasi.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Nomor Handphone')
                                    ->tel()
                                    ->default(null),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->default(null),
                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->default(null)
                                    ->columnSpanFull(),
                                TextInput::make('city')
                                    ->label('Kota')
                                    ->default(null),
                                TextInput::make('province')
                                    ->label('Provinsi')
                                    ->default(null),
                                TextInput::make('postal_code')
                                    ->label('Kode Pos')
                                    ->default(null),
                            ]),

                        Section::make('Informasi Pajak & Bank')
                            ->icon(LucideIcon::Wallet)
                            ->description('Data pajak (NPWP) dan rekening bank untuk keperluan administrasi dan pembayaran.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('npwp')
                                    ->label('NPWP')
                                    ->default(null),
                                TextInput::make('bank_name')
                                    ->label('Nama Bank')
                                    ->default(null),
                                TextInput::make('bank_account')
                                    ->label('Nomor Rekening')
                                    ->default(null),
                            ]),

                        Section::make('Keterangan Tambahan')
                            ->icon(LucideIcon::FileText)
                            ->description('Catatan khusus tentang supplier, seperti spesialisasi atau layanan khusus yang disediakan.')
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                Textarea::make('note')
                                    ->label('Keterangan')
                                    ->placeholder('cont. supplier seal, supplier ban, dll')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),

            ]);
    }
}
