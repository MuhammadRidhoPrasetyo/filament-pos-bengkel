<?php

namespace App\Filament\Widgets;

use App\Models\CashFlow;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FinancialChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Grafik Pendapatan vs Biaya';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(0, 5))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $pendapatanData = [];
        $biayaData = [];
        $storeId = auth()->user()?->store_id;
        
        foreach ($months as $month) {
            $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $end = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

            // Total Pendapatan Penjualan
            $pendapatanQuery = Transaction::where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end]);
            if ($storeId) $pendapatanQuery->where('store_id', $storeId);
            $pendapatan = $pendapatanQuery->sum('grand_total');
                
            // Harga Pokok Penjualan (HPP)
            $biayaTransactionQuery = Transaction::where('status', 'completed')
                ->whereBetween('transaction_date', [$start, $end]);
            if ($storeId) $biayaTransactionQuery->where('store_id', $storeId);
            $biayaTransaction = $biayaTransactionQuery->sum('total_cost');

            // Biaya Operasional
            $biayaOperationalQuery = CashFlow::where('type', 'expense')
                ->whereBetween('date', [$start, $end]);
            if ($storeId) $biayaOperationalQuery->where('store_id', $storeId);
            $biayaOperational = $biayaOperationalQuery->sum('amount');

            $pendapatanData[] = (float) $pendapatan;
            $biayaData[] = (float) ($biayaTransaction + $biayaOperational);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Kotor',
                    'data' => $pendapatanData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Total Biaya (HPP + Operasional)',
                    'data' => $biayaData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $months->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->translatedFormat('F Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
