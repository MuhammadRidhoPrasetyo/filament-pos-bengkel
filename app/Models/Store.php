<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Store extends Model
{
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'notes',

        'receipt_number_format',
        'receipt_sequence',
        'receipt_sequence_year',
    ];

    public function generateNextReceiptNumber(): string
    {
        return DB::transaction(function () {
            // Kunci row store ini untuk hindari race condition multi kasir
            $store = $this->lockForUpdate()->first() ?? $this->fresh(['*'])->lockForUpdate()->first();

            $now  = now();
            $year = (int) $now->year;

            // Kalau tahun berubah → reset counter ke 0
            if ($store->receipt_sequence_year !== $year) {
                $store->receipt_sequence_year = $year;
                $store->receipt_sequence      = 0;
            }

            // increment
            $store->receipt_sequence++;
            $store->save();

            $seqNumber = $store->receipt_sequence;

            // padding misal 4 digit: 0001, 0002, dsb.
            $seqPadded = str_pad($seqNumber, 4, '0', STR_PAD_LEFT);

            $format = $store->receipt_number_format ?? '{STORE_CODE}/{YYYY}/{MM}/{NUMBER}';

            // Ganti placeholder
            $number = str_replace(
                [
                    '{STORE_CODE}',
                    '{YYYY}',
                    '{YY}',
                    '{MM}',
                    '{DD}',
                    '{NUMBER}',
                ],
                [
                    $store->code ?? 'STORE',
                    $now->format('Y'),
                    $now->format('y'),
                    $now->format('m'),
                    $now->format('d'),
                    $seqPadded,
                ],
                $format
            );

            return $number;
        });
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'store_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'store_id');
    }
}
