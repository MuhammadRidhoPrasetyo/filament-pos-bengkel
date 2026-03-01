<?php

namespace App\Filament\Widgets;

use App\Models\CashFlow;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class FinancialStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $storeId = auth()->user()?->store_id;

        $totalAsetQuery = DB::table('product_stocks')
            ->join('product_prices', function ($join) {
                $join->on('product_stocks.product_id', '=', 'product_prices.product_id')
                     ->on('product_stocks.store_id', '=', 'product_prices.store_id');
            })
            ->where('product_prices.is_active', true);

        if ($storeId) {
            $totalAsetQuery->where('product_stocks.store_id', $storeId);
        }

        $totalAset = $totalAsetQuery->sum(DB::raw('product_stocks.quantity * product_prices.purchase_price'));

        // 2. Total Biaya Operasional (Bulan Ini)
        $totalBiayaQuery = CashFlow::where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth]);

        if ($storeId) {
            $totalBiayaQuery->where('store_id', $storeId);
        }
        $totalBiaya = $totalBiayaQuery->sum('amount');

        // 3. Laba Kotor (Bulan Ini)
        $labaKotorQuery = Transaction::where('status', 'completed')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);

        if ($storeId) {
            $labaKotorQuery->where('store_id', $storeId);
        }
        $labaKotor = $labaKotorQuery->sum('total_profit');

        // 4. Laba Bersih (Bulan Ini)
        $labaBersih = $labaKotor - $totalBiaya;

        return [
            Stat::make('Total Nilai Aset', 'Rp ' . number_format($totalAset, 0, ',', '.'))
                ->description('Total nilai stok barang di bengkel ini')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),

            Stat::make('Biaya Operasional (Bulan Ini)', 'Rp ' . number_format($totalBiaya, 0, ',', '.'))
                ->description('Total kas pengeluaran bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Laba Kotor (Bulan Ini)', 'Rp ' . number_format($labaKotor, 0, ',', '.'))
                ->description('Gross profit penjualan bulan ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Laba Bersih (Bulan Ini)', 'Rp ' . number_format($labaBersih, 0, ',', '.'))
                ->description('Laba kotor dikurangi biaya operasional')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($labaBersih >= 0 ? 'success' : 'danger'),
        ];
    }
}
