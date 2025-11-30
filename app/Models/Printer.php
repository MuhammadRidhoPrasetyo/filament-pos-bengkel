<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'connection_type',
        'address',
        'is_default',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

}
