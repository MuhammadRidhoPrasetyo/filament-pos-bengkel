<?php

namespace App\Filament\Resources\Printers;

use BackedEnum;
use UnitEnum;
use App\Models\Store;
use App\Models\Printer;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Mike42\Escpos\Printer as EscposPrinter;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use App\Filament\Resources\Printers\Pages\ManagePrinters;

class PrinterResource extends Resource
{
    protected static ?string $model = Printer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | UnitEnum | null $navigationGroup = 'Pengaturan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('store_id')
                    ->label('Bengkel')
                    ->options(Store::all()->pluck('name', 'id')),
                TextInput::make('name')
                    ->label('Nama Printer')
                    ->required(),
                Select::make('connection_type')
                    ->label('Tipe Koneksi')
                    ->options(['usb' => 'Usb', 'network' => 'Network', 'bluetooth' => 'Bluetooth'])
                    ->required(),
                TextInput::make('address')
                    ->label('Alamat Printer / Nama Printer')
                    ->required(),
                Toggle::make('is_default')
                    ->label('Printer Utama')
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('store_id'),
                TextEntry::make('name'),
                TextEntry::make('connection_type')
                    ->badge(),
                TextEntry::make('address'),
                IconEntry::make('is_default')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('store.name')
                    ->label('Bengkel')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Printer')
                    ->searchable(),
                TextColumn::make('connection_type')
                    ->label('Tipe Koneksi')
                    ->badge(),
                TextColumn::make('address')
                    ->label('Alamat Printer / Nama Printer')
                    ->searchable(),
                IconColumn::make('is_default')
                    ->label('Printer Utama')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('testPrinter')
                    ->label('Test Printer')
                    ->action(function (Printer $record) {
                        try {
                            // --- Tentukan konektor printer ---
                            if ($record->connection_type === 'network') {
                                $connector = new NetworkPrintConnector($record->address, 9100);
                            } else if ($record->connection_type === 'usb') {
                                $connector = new WindowsPrintConnector($record->address);
                            } else {
                                throw new \Exception("Tipe koneksi tidak dikenali.");
                            }

                            // --- Mulai test print ---
                            $printerDevice = new EscposPrinter($connector);

                            $printerDevice->text("TEST PRINT\n");
                            $printerDevice->cut();
                            $printerDevice->close();

                            // --- Notifikasi sukses ---
                            Notification::make()
                                ->title('Print test berhasil 🎉')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {

                            // --- Notifikasi error ---
                            Notification::make()
                                ->title('Print test gagal ❌')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePrinters::route('/'),
        ];
    }
}
