<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Store;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Tanggal Verifikasi'),
                TextInput::make('password')
                    ->password()
                    ->required(fn($livewire) => $livewire instanceof CreateUser),
                TextInput::make('nik')
                    ->default(null)
                    ->label('NIK'),
                TextInput::make('phone')
                    ->tel()
                    ->default(null)
                    ->label('Nomor Handphone'),
                TextInput::make('address')
                    ->default(null)
                    ->label('Alamat'),
                Select::make('store_id')
                    ->label('Terdaftar Pada Bengkel')
                    ->options(Store::all()->pluck('id', 'name'))
                    ->relationship('store', 'name')
                    ->required(),
                Select::make('role')
                    ->label('Roles')
                    ->options(Role::all()->pluck('id', 'name'))
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->required(),
                Toggle::make('active')
                    ->label('Status Pengguna')
                    ->required(),
            ]);
    }
}
