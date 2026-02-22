<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Store;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                        Section::make('Informasi Akun')
                            ->icon(LucideIcon::User)
                            ->description('Data login: nama dan email untuk akses sistem.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->columnSpan(1),
                                TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn($livewire) => $livewire instanceof CreateUser)
                                    ->hidden(fn($livewire) => $livewire instanceof EditUser)
                                    ->helperText('Minimal 8 karakter. Kosongkan jika tidak ingin mengubah.')
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Informasi Pribadi')
                            ->icon(LucideIcon::IdCard)
                            ->description('Data identitas dan kontak untuk profil pengguna.')
                            ->columns(2)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                TextInput::make('nik')
                                    ->label('NIK (KTP)')
                                    ->default(null)
                                    ->columnSpan(1),
                                TextInput::make('phone')
                                    ->label('Nomor Handphone')
                                    ->tel()
                                    ->default(null)
                                    ->columnSpan(1),
                                TextInput::make('address')
                                    ->label('Alamat')
                                    ->default(null)
                                    ->columnSpanFull()
                                    ->helperText('Alamat tinggal untuk file kepegawaian.'),
                            ]),

                        Section::make('Penugasan & Privilege')
                            ->icon(LucideIcon::Lock)
                            ->description('Tentukan bengkel dan role untuk menentukan akses dan tanggung jawab pengguna.')
                            ->columns(1)
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                Select::make('store_id')
                                    ->label('Bengkel Terdaftar')
                                    ->options(Store::all()->pluck('name', 'id'))
                                    ->relationship('store', 'name')
                                    ->required()
                                    ->helperText('Pilih bengkel utama tempat pengguna bekerja.'),
                                Select::make('role')
                                    ->label('Roles / Peran')
                                    ->options(Role::all()->pluck('name', 'id'))
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->required()
                                    ->helperText('Pilih satu atau lebih peran untuk mendefinisikan permission.'),
                                Toggle::make('active')
                                    ->label('Status Pengguna Aktif')
                                    ->required()
                                    ->helperText('Nonaktifkan untuk melarang pengguna login.'),
                            ]),

                        Section::make('Verifikasi Email')
                            ->icon(LucideIcon::Mail)
                            ->description('Status verifikasi email pengguna.')
                            ->columnSpan([
                                'sm' => 12,
                                'md' => 8,
                                'lg' => 8,
                            ])
                            ->schema([
                                DateTimePicker::make('email_verified_at')
                                    ->label('Tanggal Verifikasi Email')
                                    ->disabled()
                                    ->helperText('Waktu saat pengguna memverifikasi email akun mereka.'),
                            ]),

            ]);
    }
}
