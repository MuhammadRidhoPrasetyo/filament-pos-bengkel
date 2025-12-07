<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DocumentSequence extends Model
{
    use HasUuids;

    protected $fillable = [
        'type',
        'store_id',
        'sequence',
        'year',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
