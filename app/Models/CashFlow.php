<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CashFlow extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CashFlowCategory::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
