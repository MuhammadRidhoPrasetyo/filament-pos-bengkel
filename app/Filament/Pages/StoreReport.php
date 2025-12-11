<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Form;
use Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf;
use BackedEnum;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class StoreReport extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected string $view = 'filament.pages.store-report';
    protected static ?string $title = 'Laporan Toko';
    protected static ?string $navigationLabel = 'Laporan';
    protected static string | BackedEnum | null $navigationIcon = LucideIcon::PrinterCheck;
    protected static bool $shouldRegisterNavigation = false;
    // protected static string | BackedEnum $navigationIcon = 'heroicon-o-chart-bar';


    // Filter state
    public ?string $storeId = null;
    public ?int $cashierId = null;
    public ?int $categoryId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo   = null;

    // Summary
    public array $summary = [
        'omzet'       => 0,
        'pengeluaran' => 0,
        'profit'      => 0,
    ];

    // Data
    public array $barangMasuk  = [];
    public array $barangKeluar = [];

    // Heatmap hourly sales
    public array $hourlySales = [];

    // public function mount(): void
    // {
    //     $this->form->fill([
    //         'store_id'  => null,
    //         'cashier_id' => null,
    //         'category_id' => null,
    //         'date_from' => Carbon::now()->startOfMonth()->format('Y-m-d'),
    //         'date_to'   => Carbon::now()->endOfMonth()->format('Y-m-d'),
    //     ]);

    //     $this->filter();
    // }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('store_id')
                    ->label('Toko')
                    ->options(Store::all()->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Semua Toko'),

                Select::make('cashier_id')
                    ->label('Kasir')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Semua Kasir'),

                Select::make('category_id')
                    ->label('Kategori Produk')
                    ->options(ProductCategory::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Semua Kategori'),

                DatePicker::make('date_from')->label('Dari')
                    ->reactive(),
                DatePicker::make('date_to')->label('Sampai')
                    ->reactive(),
            ]);
    }

    public function filter(): void
    {
        // dd($this->form);
        $state = $this->form->getState();

        $storeId  = $this->storeId;
        $cashierId = $this->cashierId;
        $categoryId = $this->categoryId;
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;

        $from = Carbon::parse($this->dateFrom)->startOfDay();
        $to   = Carbon::parse($this->dateTo)->endOfDay();

        /** =====================
         * 1) OMZET ======================*/
        $omzetQuery = DB::table('transactions')
            ->whereBetween('transaction_date', [$from, $to]);

        if ($this->storeId)   $omzetQuery->where('store_id', $this->storeId);
        if ($this->cashierId) $omzetQuery->where('cashier_id', $this->cashierId);

        $omzet = $omzetQuery->sum('grand_total');
        $profit = $omzetQuery->sum('total_profit');

        /** =====================
         * 2) PENGELUARAN ======================*/
        $expQuery = DB::table('purchases')
            ->whereBetween('purchase_date', [$from, $to]);

        if ($this->storeId)   $expQuery->where('store_id', $this->storeId);

        $pengeluaran = $expQuery->sum('price');

        /** PROFIT */
        $this->summary = [
            'omzet'       => $omzet,
            'pengeluaran' => $pengeluaran,
            'profit'      => $profit,
        ];

        /** =====================
         * 3) Barang Masuk ======================*/
        $bm = DB::table('product_movements')
            ->join('products', 'product_movements.product_id', '=', 'products.id')
            ->select('products.name', 'quantity', 'occurred_at', 'note')
            ->where('movement_type', 'in')
            ->whereBetween('occurred_at', [$from, $to]);

        if ($this->storeId)   $bm->where('store_id', $this->storeId);
        if ($this->categoryId) $bm->where('products.product_category_id', $this->categoryId);

        $this->barangMasuk = $bm->get()->toArray();

        /** =====================
         * 4) Barang Keluar ======================*/
        $bk = DB::table('product_movements')
            ->join('products', 'product_movements.product_id', '=', 'products.id')
            ->select('products.name', 'quantity', 'occurred_at', 'note')
            ->where('movement_type', 'out')
            ->whereBetween('occurred_at', [$from, $to]);

        if ($this->storeId)   $bk->where('store_id', $this->storeId);
        if ($this->categoryId) $bk->where('products.product_category_id', $this->categoryId);

        $this->barangKeluar = $bk->get()->toArray();

        /** =====================
         * 5) Heatmap Per Jam ======================*/
        $hourSales = [];

        for ($i = 0; $i < 24; $i++) $hourSales[$i] = 0;

        $result = DB::table('transactions')
            ->selectRaw('HOUR(transaction_date) as hour, SUM(grand_total) as total')
            ->whereBetween('transaction_date', [$from, $to])
            ->groupBy('hour')
            ->get();

        foreach ($result as $row) {
            $hourSales[$row->hour] = $row->total;
        }

        $this->hourlySales = $hourSales;
    }

    // public function exportPdf()
    // {
    //     $pdf = Pdf::loadView('exports.store-report-pdf', [
    //         'summary'       => $this->summary,
    //         'barangMasuk'   => $this->barangMasuk,
    //         'barangKeluar'  => $this->barangKeluar,
    //         'hourlySales'   => $this->hourlySales,
    //         'dateFrom'      => $this->dateFrom,
    //         'dateTo'        => $this->dateTo,
    //     ]);

    //     return response()->streamDownload(fn() => print($pdf->output()), 'laporan.pdf');
    // }
}
