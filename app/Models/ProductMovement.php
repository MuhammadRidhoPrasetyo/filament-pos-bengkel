<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ProductMovementObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

class ProductMovement extends Model
{
    use HasUuids;

    protected $fillable = [
        'product_id',
        'store_id',
        'movement_type',
        'quantity',
        'movementable_type',
        'movementable_id',
        'occurred_at',
        'created_by',
        'note',
    ];

    public function movementable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
