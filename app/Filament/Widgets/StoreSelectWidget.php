<?php

namespace App\Filament\Widgets;

use App\Models\Store;
use Filament\Widgets\Widget;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class StoreSelectWidget extends Widget
{
    protected string $view = 'filament.widgets.store-select-widget';
    public $storeId;
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public function mount()
    {
        $this->storeId = Auth::user()->store_id;
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('owner');
    }

    public function updatedStoreId()
    {
        $user = Auth::user();
        $user->store_id = $this->storeId;
        $user->save();
        Notification::make()
            ->success()
            ->title('Bengkel berhasil diubah')
            ->send();
    }

    #[Computed()]
    public function stores()
    {
        return Store::all()->pluck('name', 'id');
    }
}
