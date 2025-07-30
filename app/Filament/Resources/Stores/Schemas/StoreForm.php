<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Bengkel')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Bengkel')
                    ->required(),
                TextInput::make('phone')
                    ->label('Nomor Handphone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->default(null),
                TextInput::make('address')
                    ->label('Alamat')
                    ->default(null),
                TextInput::make('city')
                    ->label('Kota')
                    ->default(null),
                TextInput::make('province')
                    ->label('Provinsi')
                    ->default(null),
                TextInput::make('postal_code')
                    ->label('Kode Pos')
                    ->default(null),
                Textarea::make('notes')
                    ->label('Keterangan')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
