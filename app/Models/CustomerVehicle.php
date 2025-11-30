<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CustomerVehicle extends Model
{
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'plate_number',
        'brand',
        'model',
        'year',
        'color',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id'); // atau Customer
    }

}
