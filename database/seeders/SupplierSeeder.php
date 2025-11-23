<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP001',
                'name' => 'PT Sumber Makmur',
                'contact_person' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'info@sumbermakmur.co.id',
                'address' => 'Jl. Merdeka No. 123',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10110',
                'npwp' => '01.234.567.8-999.000',
                'bank_name' => 'BCA',
                'bank_account' => '1234567890',
                'notes' => 'Supplier utama bahan baku',
            ],
            [
                'code' => 'SUP002',
                'name' => 'CV Maju Bersama',
                'contact_person' => 'Siti Aminah',
                'phone' => '082345678901',
                'email' => 'contact@majubersama.com',
                'address' => 'Jl. Gatot Subroto No. 45',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'postal_code' => '40123',
                'npwp' => '02.345.678.9-111.000',
                'bank_name' => 'Mandiri',
                'bank_account' => '9876543210',
                'notes' => 'Supplier sparepart mesin',
            ],
            [
                'code' => 'SUP003',
                'name' => 'UD Sejahtera',
                'contact_person' => 'Andi Wijaya',
                'phone' => '083456789012',
                'email' => 'sales@udsejahtera.id',
                'address' => 'Jl. Diponegoro No. 78',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'postal_code' => '60234',
                'npwp' => null,
                'bank_name' => 'BRI',
                'bank_account' => '555666777888',
                'notes' => 'Pemasok produk lokal',
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::create(array_merge($data, [
                'id' => Str::uuid(),
            ]));
        }
    }
}
