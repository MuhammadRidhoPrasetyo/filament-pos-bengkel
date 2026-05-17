<?php

namespace App\Filament\Resources\CashFlows\Widgets;

use App\Models\CashFlow;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\CashFlows\Pages\ListCashFlows;

class CashFlowStatsWidget extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListCashFlows::class;
    }

    public function getColumns(): int | array
    {
        return [
            'md' => 12,
            'xl' => 12,
        ];
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        $format = fn(float $amount): string => 'Rp ' . number_format($amount, 0, ',', '.');

        $cloneClean = function () use ($query) {
            $q = clone $query;
            $q->getQuery()->orders = null;
            $q->getQuery()->limit = null;
            $q->getQuery()->offset = null;
            return $q;
        };

        $totalIncome = (float) $cloneClean()
            ->where('type', 'income')
            ->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->value('total');

        $totalExpense = (float) $cloneClean()
            ->where('type', 'expense')
            ->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->value('total');

        $balance = $totalIncome - $totalExpense;

        $totalCount = (int) $cloneClean()->count();
        $incomeCount = (int) $cloneClean()->where('type', 'income')->count();
        $expenseCount = (int) $cloneClean()->where('type', 'expense')->count();

        return [
            Stat::make('Kas Masuk', $format($totalIncome))
                ->description("{$incomeCount} transaksi masuk")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->columnSpan(4),

            Stat::make('Kas Keluar', $format($totalExpense))
                ->description("{$expenseCount} transaksi keluar")
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->columnSpan(4),

            Stat::make('Saldo', $format($balance))
                ->description($balance >= 0 ? 'Surplus' : 'Defisit')
                ->descriptionIcon($balance >= 0 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->color($balance >= 0 ? 'success' : 'danger')
                ->columnSpan(4),
        ];
    }
}
