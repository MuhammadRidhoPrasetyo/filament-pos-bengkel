<?php

namespace App\Traits;

use App\Models\DocumentSequence;
use Illuminate\Support\Facades\DB;

trait HasDocumentNumber
{
    public function generateDocumentNumber(string $type, string $format = '{TYPE}/{YYYY}/{MM}/{NUMBER}', ?string $storeId = null): string
    {
        return DB::transaction(function () use ($type, $format, $storeId) {

            $now  = now();
            $year = (int) $now->year;

            $row = DocumentSequence::lockForUpdate()->firstOrCreate(
                [
                    'type'     => $type,
                    'store_id' => $storeId,
                    'year'     => $year,
                ],
                [
                    'sequence' => 0,
                    'year'     => $year,
                ]
            );

            // increment
            $row->sequence++;
            $row->save();

            // padding 4 digit
            $seq = str_pad($row->sequence, 4, '0', STR_PAD_LEFT);

            // Replace placeholder
            return str_replace(
                ['{TYPE}', '{YYYY}', '{YY}', '{MM}', '{DD}', '{NUMBER}'],
                [
                    $type,
                    $now->format('Y'),
                    $now->format('y'),
                    $now->format('m'),
                    $now->format('d'),
                    $seq,
                ],
                $format
            );
        });
    }
}
