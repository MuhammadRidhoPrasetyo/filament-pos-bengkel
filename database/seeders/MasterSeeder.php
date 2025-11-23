<?php

namespace Database\Seeders;


use App\Models\Unit;
use App\Models\Brand;
use App\Models\Store;
use Illuminate\Support\Str;
use App\Models\DiscountType;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::insert([
            [
                'id'          => (string) Str::uuid(),
                'code'        => 'T01',
                'name'        => 'Toko Pusat',
                'phone'       => '021-123456',
                'email'       => 'pusat@example.com',
                'address'     => 'Jl. Sudirman No.1',
                'city'        => 'Jakarta',
                'province'    => 'DKI Jakarta',
                'postal_code' => '10210',
                'notes'       => 'Cabang utama',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => (string) Str::uuid(),
                'code'        => 'T02',
                'name'        => 'Cabang A',
                'phone'       => '021-654321',
                'email'       => 'cabang-a@example.com',
                'address'     => 'Jl. Gatot Subroto No.2',
                'city'        => 'Bandung',
                'province'    => 'Jawa Barat',
                'postal_code' => '40123',
                'notes'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id'          => (string) Str::uuid(),
                'code'        => 'T03',
                'name'        => 'Cabang B',
                'phone'       => '031-888888',
                'email'       => 'cabang-b@example.com',
                'address'     => 'Jl. Pemuda No.3',
                'city'        => 'Surabaya',
                'province'    => 'Jawa Timur',
                'postal_code' => '60231',
                'notes'       => 'Cabang dengan gudang besar',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // Discount Type
        DiscountType::insert([
            ['id' => 1, 'name' => 'Promo Musiman', 'description' => 'Diskon untuk musim tertentu', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Member Only', 'description' => 'Diskon khusus member', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Clearance Sale', 'description' => 'Diskon barang cuci gudang', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Product Category
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

        // Brand
        Brand::insert([
            ['id' => 1, 'name' => 'Yamaha', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Honda', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Suzuki', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Unit
        Unit::insert([
            ['id' => 1, 'name' => 'Pcs', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Dus', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Pack', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
