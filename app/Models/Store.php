<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
