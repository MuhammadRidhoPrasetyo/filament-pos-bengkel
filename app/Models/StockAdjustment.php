<?php

namespace App\Models;

use App\Observers\StockAdjustmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

#[ObservedBy(StockAdjustmentObserver::class)]
class StockAdjustment extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_id',
        'posted_by',
        'reference_number',
        'occurred_at',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
