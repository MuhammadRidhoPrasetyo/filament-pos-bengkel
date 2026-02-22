<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Profil Bengkel')
                            ->icon(LucideIcon::Building)
                            ->description('Informasi dasar bengkel: nama, kode unik, dan kontak yang mudah dihubungi.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('code')
                                    ->label('Kode Bengkel')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('name')
                                    ->label('Nama Bengkel')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('phone')
                                    ->label('Nomor Handphone')
                                    ->tel()
                                    ->default(null)
                                    ->columnSpan(1),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->default(null)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),

                        Section::make('Lokasi Bengkel')
                            ->icon(LucideIcon::MapPin)
                            ->description('Rincian alamat lengkap untuk memudahkan pelanggan menemukan lokasi bengkel.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('address')
                                    ->label('Alamat')
                                    ->default(null)
                                    ->columnSpanFull(),
                                TextInput::make('city')
                                    ->label('Kota')
                                    ->default(null)
                                    ->columnSpan(1),
                                TextInput::make('province')
                                    ->label('Provinsi')
                                    ->default(null)
                                    ->columnSpan(1),
                                TextInput::make('postal_code')
                                    ->label('Kode Pos')
                                    ->default(null)
                                    ->columnSpan(1),
                            ])
                            ->columnSpanFull(),

                        Section::make('Keterangan Tambahan')
                            ->icon(LucideIcon::FileText)
                            ->description('Catatan atau informasi penting tentang bengkel yang perlu digetahui.')
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                Textarea::make('notes')
                                    ->label('Keterangan')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
