<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StoreInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->hidden(),
                TextEntry::make('code')
                ->label('Kode Bengkel'),
                TextEntry::make('name')
                ->label('Nama Bengkel'),
                TextEntry::make('phone')
                ->label('Nomor Handphone'),
                TextEntry::make('email')
                ->label('Email'),
                TextEntry::make('address')
                ->label('Alamat'),
                TextEntry::make('city')
                ->label('Kota'),
                TextEntry::make('province')
                ->label('Provinsi'),
                TextEntry::make('postal_code')
                ->label('Kode Pos'),
                TextEntry::make('created_at')
                    ->hidden()
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->hidden()
                    ->dateTime(),
            ]);
    }
}
