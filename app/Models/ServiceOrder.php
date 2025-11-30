<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrder extends Model
{
    use HasUuids;

    protected $fillable = [
        'number',
        'store_id',
        'customer_id',
        'status',
        'checkin_at',
        'completed_at',
        'general_complaint',
        'estimated_total',
        'transaction_id',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id'); // atau Customer
    }

    public function units()
    {
        return $this->hasMany(ServiceOrderUnit::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
