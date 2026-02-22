<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                        Section::make('Informasi Akun')
                            ->icon(LucideIcon::User)
                            ->description('Data login dan profil pengguna dalam sistem.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap')
                                    ->weight('semibold')
                                    ->columnSpan(1),
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->columnSpan(1)
                                    ->copyable(),
                                TextEntry::make('email_verified_at')
                                    ->label('Status Verifikasi Email')
                                    ->dateTime()
                                    ->columnSpan(2)
                                    ->hidden(fn($record) => !$record->email_verified_at)
                                    ->badge()
                                    ->color('success'),
                            ])
                            ->columnSpanFull(),

                        Section::make('Informasi Pribadi')
                            ->icon(LucideIcon::IdCard)
                            ->description('Data identitas dan kontak pribadi pengguna.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('nik')
                                    ->label('NIK (KTP)')
                                    ->columnSpan(1)
                                    ->hidden(fn($record) => !$record->nik),
                                TextEntry::make('phone')
                                    ->label('Nomor Handphone')
                                    ->columnSpan(1)
                                    ->copyable()
                                    ->hidden(fn($record) => !$record->phone),
                                TextEntry::make('address')
                                    ->label('Alamat')
                                    ->columnSpan(2)
                                    ->hidden(fn($record) => !$record->address),
                            ]),

                        Section::make('Penugasan & Privilege')
                            ->icon(LucideIcon::Lock)
                            ->description('Bengkel dan peran untuk menentukan akses dan tanggung jawab.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextEntry::make('store.name')
                                    ->label('Bengkel Terdaftar')
                                    ->badge()
                                    ->color('warning')
                                    ->columnSpan(1),
                                TextEntry::make('active')
                                    ->label('Status Pengguna')
                                    ->columnSpan(1)
                                    ->badge()
                                    ->color(fn(bool $state): string => $state ? 'success' : 'danger')
                                    ->formatStateUsing(fn(bool $state): string => $state ? 'Aktif' : 'Nonaktif'),
                            ]),

                        Section::make('Audit Trail')
                            ->icon(LucideIcon::History)
                            ->description('Riwayat pembuatan dan pembaruan data pengguna.')
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
