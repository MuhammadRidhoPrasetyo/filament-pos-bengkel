<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Suppliers\Pages\CreateSupplier;
use Filament\Forms\Components\Select;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                TextInput::make('contact_person')
                    ->label('Kontak Person')
                    ->default(null),
                TextInput::make('phone')
                    ->label('Nomor Handphone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->default(null),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'supplier' => 'Supplier',
                        'customer' => 'Customer',
                        'both' => 'Supplier & Customer',
                    ]),
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
                TextInput::make('npwp')
                    ->label('NPWP')
                    ->default(null),
                TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->default(null),
                TextInput::make('bank_account')
                    ->label('Nomor Rekening')
                    ->default(null),
                Textarea::make('note')
                    ->label('Keterangan')
                    ->placeholder('cont. supplier seal, supplier ban, dll')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
