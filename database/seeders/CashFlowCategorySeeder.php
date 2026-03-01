<?php

namespace Database\Seeders;

use App\Models\CashFlowCategory;
use Illuminate\Database\Seeder;

class CashFlowCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Penjualan',
                'type' => 'income',
                'description' => 'Pemasukan otomatis dari transaksi penjualan',
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'Pembelian Stok',
                'type' => 'expense',
                'description' => 'Pengeluaran otomatis dari pembelian stok barang',
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($categories as $category) {
            CashFlowCategory::updateOrCreate(
                ['name' => $category['name'], 'is_system' => true],
                $category
            );
        }
    }
}
