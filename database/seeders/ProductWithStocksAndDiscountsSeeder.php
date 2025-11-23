<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\DiscountType;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductWithStocksAndDiscountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // --- Pastikan master minimal ada ---
            $stores = Store::pluck('id')->all();
            if (empty($stores)) {
                $stores = [
                    (string) Str::uuid(),
                    (string) Str::uuid(),
                ];
                Store::insert([
                    ['id' => $stores[0], 'name' => 'Toko Pusat', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => $stores[1], 'name' => 'Cabang A', 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            $discountTypes = DiscountType::pluck('id')->all();
            if (empty($discountTypes)) {
                DiscountType::insert([
                    ['id' => 1, 'name' => 'Promo Musiman', 'description' => 'Diskon musim tertentu', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'Member Only', 'description' => 'Diskon khusus member', 'created_at' => now(), 'updated_at' => now()],
                ]);
                $discountTypes = DiscountType::pluck('id')->all();
            }

            $categories = ProductCategory::pluck('id')->all();
            if (empty($categories)) {
                ProductCategory::insert([
                    [
                        'id'           => 1,
                        'name'         => 'Sparepart Motor',
                        'pricing_mode' => 'fixed',   // harga tetap
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                    [
                        'id'           => 2,
                        'name'         => 'Sparepart Mobil',
                        'pricing_mode' => 'editable', // harga bisa disesuaikan
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                    [
                        'id'           => 3,
                        'name'         => 'Aksesoris',
                        'pricing_mode' => 'fixed',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                    [
                        'id'           => 4,
                        'name'         => 'Oli & Pelumas',
                        'pricing_mode' => 'editable',
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                ]);
                $categories = ProductCategory::pluck('id')->all();
            }

            $brands = Brand::pluck('id')->all();
            if (empty($brands)) {
                Brand::insert([
                    ['id' => 1, 'name' => 'Yamaha', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'Honda', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 3, 'name' => 'Suzuki', 'created_at' => now(), 'updated_at' => now()],
                ]);
                $brands = Brand::pluck('id')->all();
            }

            $units = Unit::pluck('id')->all();
            if (empty($units)) {
                Unit::insert([
                    ['id' => 1, 'name' => 'Pcs', 'created_at' => now(), 'updated_at' => now()],
                    ['id' => 2, 'name' => 'Dus', 'created_at' => now(), 'updated_at' => now()],
                ]);
                $units = Unit::pluck('id')->all();
            }

            // --- Helper kecil ---
            $randPick = fn(array $arr) => $arr[array_rand($arr)];

            // --- Buat 10 produk, masing-masing dengan stocks & discounts ---
            for ($i = 1; $i <= 20; $i++) {
                $product = Product::create([
                    'id'                  => (string) Str::uuid(),
                    'product_category_id' => $randPick($categories),
                    'brand_id'            => $randPick($brands),
                    'unit_id'             => $randPick($units),
                    'sku'                 => 'SKU-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                    'name'                => 'Produk ' . $i,
                    'type'                => ['sparepart', 'aksesoris'][array_rand(['sparepart', 'aksesoris'])],
                    'keyword'             => 'motor, servis, part',
                    'compatibility'       => 'Yamaha; Honda; Suzuki',
                    'size'                => 'M',
                    'unit'                => null, // jika kamu memang punya kolom 'unit' string di tabel products
                    'description'         => 'Deskripsi singkat produk ' . $i,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                // --- Stocks (sesuai Repeater: store_id, date, quantity)
                // 1–2 baris stok untuk store-store berbeda
                $storeIdsForStock = array_slice($stores, 0, min(2, count($stores)));
                $stocksPayload = [];
                foreach ($storeIdsForStock as $idx => $storeId) {
                    $stocksPayload[] = [
                        'id'         => (string) Str::uuid(),
                        'store_id'   => $storeId,
                        'quantity'   => rand(10, 150),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                // jika Product::stocks() -> relationship('stocks') sudah ada:
                $product->stocks()->createMany($stocksPayload);

                // --- Discounts (sesuai Repeater: store_id, discount_type_id, type, value)
                // 0–2 baris diskon
                $discountRows = rand(0, 2);
                $discountsPayload = [];
                for ($d = 0; $d < $discountRows; $d++) {
                    $discType = $randPick(['percent', 'amount']);
                    $value    = $discType === 'percent'
                        ? rand(5, 30)             // 5%–30%
                        : rand(5000, 50000);      // Rp 5.000 – 50.000

                    $discountsPayload[] = [
                        'id'                => (string) Str::uuid(),
                        'store_id'          => $randPick($stores),
                        'discount_type_id'  => $randPick($discountTypes),
                        'type'              => $discType,
                        'value'             => $value,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }
                if (!empty($discountsPayload)) {
                    $product->discounts()->createMany($discountsPayload);
                }
            }
        });
    }
}
