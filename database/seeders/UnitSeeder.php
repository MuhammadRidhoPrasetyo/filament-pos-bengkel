<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Umum
            ['name' => 'Pieces', 'symbol' => 'pcs'],
            ['name' => 'Set', 'symbol' => 'set'],

            // Volume
            ['name' => 'Liter', 'symbol' => 'L'],
            ['name' => 'Mililiter', 'symbol' => 'mL'],
            ['name' => 'Galon', 'symbol' => 'gal'],

            // Berat
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Gram', 'symbol' => 'g'],

            // Panjang
            ['name' => 'Meter', 'symbol' => 'm'],
            ['name' => 'Centimeter', 'symbol' => 'cm'],
            ['name' => 'Milimeter', 'symbol' => 'mm'],

            // Waktu
            ['name' => 'Jam', 'symbol' => 'jam'],
            ['name' => 'Menit', 'symbol' => 'menit'],

            // Otomotif Umum
            ['name' => 'Botol', 'symbol' => 'btl'],
            ['name' => 'Kaleng', 'symbol' => 'klg'],
            ['name' => 'Roll', 'symbol' => 'roll'],
            ['name' => 'Tube', 'symbol' => 'tube'],
        ];

        DB::table('units')->insert($units);
    }
}
