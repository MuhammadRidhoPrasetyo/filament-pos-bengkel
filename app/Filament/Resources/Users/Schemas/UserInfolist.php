<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label('Nama'),
                TextEntry::make('email')
                ->label('Email'),
                TextEntry::make('email_verified_at')
                    ->hidden()
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->hidden()
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->hidden()
                    ->dateTime(),
                TextEntry::make('nik')
                    ->label('NIK'),
                TextEntry::make('phone')
                    ->label('Nomor Handphone'),
                TextEntry::make('address')
                    ->label('Alamat'),
                TextEntry::make('store.name')
                    ->label('Terdaftar Pada Bengkel'),
                IconEntry::make('active')
                    ->label('Status Pengguna')
                    ->boolean(),
            ]);
    }
}
