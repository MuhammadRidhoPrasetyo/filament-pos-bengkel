<?php

namespace App\Models;

use App\Observers\StockAdjustmentItemObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(StockAdjustmentItemObserver::class)]
class StockAdjustmentItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'stock_adjustment_id',
        'product_id',
        'adjustment_type',
        'quantity',
        'note',
    ];

    public function movement()
    {
        return $this->morphOne(ProductMovement::class, 'movementable');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
