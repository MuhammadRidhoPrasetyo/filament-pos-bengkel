<?php

namespace App\Filament\Resources\StockTransfers\Pages;

use App\Filament\Resources\StockTransfers\StockTransferResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Carbon\Carbon;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;
use Illuminate\Support\Facades\Auth;

class ViewStockTransfer extends ViewRecord
{
    protected static string $resource = StockTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('post')
                ->label('Post')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status !== 'posted')
                ->action(function () {
                    try {
                        $this->record->post(Auth::user());
                        $this->notify('success', 'Transfer berhasil diposting.');
                        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                    } catch (\Throwable $e) {
                        $this->notify('danger', 'Posting gagal: ' . $e->getMessage());
                    }
                }),

            Action::make('cancel')
                ->label('Cancel')
                ->icon(LucideIcon::XCircle)
                ->requiresConfirmation()
                ->visible(fn () => (
                    $this->record->status === 'posted'
                    && $this->record->posted_at
                    && Carbon::now()->diffInHours($this->record->posted_at) <= 24
                    && (Auth::id() === $this->record->created_by || Auth::id() === $this->record->posted_by)
                ))
                ->action(function () {
                    try {
                        $this->record->cancel(Auth::user());
                        $this->notify('success', 'Transfer berhasil dibatalkan.');
                        $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                    } catch (\Throwable $e) {
                        $this->notify('danger', 'Pembatalan gagal: ' . $e->getMessage());
                    }
                }),
        ];
    }
}
