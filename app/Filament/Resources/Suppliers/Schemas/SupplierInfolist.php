<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                        Section::make('Informasi Dasar')
                            ->icon(LucideIcon::Users)
                            ->description('Detail identitas supplier atau customer pusat.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('code')
                                    ->label('Kode Supplier')
                                    ->badge()
                                    ->weight('semibold')
                                    ->columnSpan(1),
                                TextEntry::make('name')
                                    ->label('Nama')
                                    ->weight('semibold')
                                    ->columnSpan(1),
                                TextEntry::make('contact_person')
                                    ->label('Kontak Person')
                                    ->columnSpan(2),
                            ]),

                        Section::make('Informasi Kontak')
                            ->icon(LucideIcon::Phone)
                            ->description('Nomor telepon, email, dan alamat lengkap.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('phone')
                                    ->label('Nomor Handphone')
                                    ->icon('heroicon-o-phone')
                                    ->columnSpan(1)
                                    ->copyable(),
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->columnSpan(1)
                                    ->copyable(),
                                TextEntry::make('city')
                                    ->label('Kota')
                                    ->columnSpan(1),
                                TextEntry::make('province')
                                    ->label('Provinsi')
                                    ->columnSpan(1),
                                TextEntry::make('postal_code')
                                    ->label('Kode Pos')
                                    ->columnSpan(2),
                            ]),

                        Section::make('Informasi Pajak & Bank')
                            ->icon(LucideIcon::Wallet)
                            ->description('Data pajak dan rekening bank untuk keperluan administrasi.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('npwp')
                                    ->label('NPWP')
                                    ->columnSpan(2)
                                    ->badge()
                                    ->color('warning'),
                                TextEntry::make('bank_name')
                                    ->label('Nama Bank')
                                    ->columnSpan(1),
                                TextEntry::make('bank_account')
                                    ->label('Nomor Rekening')
                                    ->columnSpan(1)
                                    ->copyable(),
                            ]),

                        Section::make('Audit Trail')
                            ->icon(LucideIcon::History)
                            ->description('Catatan kapan data ini dibuat dan diperbarui terakhir.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime()
                                    ->columnSpan(1),
                                TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->dateTime()
                                    ->columnSpan(1),
                            ]),


            ]);
    }
}
