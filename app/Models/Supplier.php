<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'npwp',
        'bank_name',
        'bank_account',
        'notes',
    ];

    protected static function booted()
    {
        static::creating(function ($supplier) {
            // UUID
            $supplier->id = Str::uuid();

            // Auto-generate supplier code
            $latestCode = DB::table('suppliers')
                ->where('code', 'like', 'SUPP-%')
                ->orderByDesc('code')
                ->value('code');

            if ($latestCode) {
                $lastNumber = (int) str_replace('SUPP-', '', $latestCode);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $supplier->code = 'SUPP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    public static function code()
    {
        // Auto-generate supplier code
        $latestCode = DB::table('suppliers')
            ->where('code', 'like', 'SUPP-%')
            ->orderByDesc('code')
            ->value('code');

        if ($latestCode) {
            $lastNumber = (int) str_replace('SUPP-', '', $latestCode);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return  'SUPP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
