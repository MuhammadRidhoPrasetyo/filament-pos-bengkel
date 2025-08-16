<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_id',
        'supplier_id',
        'created_by',
        'received_by',
        'number',
        'invoice_number',
        'purchase_date',
        'discount_type',
        'discount_value',
        'price',
        'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number = (string) 'BRG-' . now()->timestamp . now()->micro;
        });
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
