<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID')
                    ->hidden(),
                TextEntry::make('code')
                    ->label('Kode Supplier'),
                TextEntry::make('name')
                    ->label('Nama'),
                TextEntry::make('contact_person')
                    ->label('Kontak Person'),
                TextEntry::make('phone')
                    ->label('Nomor Handphone'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('city')
                    ->label('Kota'),
                TextEntry::make('province')
                    ->label('Provinsi'),
                TextEntry::make('postal_code')
                    ->label('Kode Pos'),
                TextEntry::make('npwp')
                    ->label('NPWP'),
                TextEntry::make('bank_name')
                    ->label('Nama Bank'),
                TextEntry::make('bank_account')
                    ->label('Nomor Rekening'),
                TextEntry::make('created_at')
                    ->label('created_at')
                    ->hidden()
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('updated_at')
                    ->hidden()
                    ->dateTime(),
            ]);
    }
}
