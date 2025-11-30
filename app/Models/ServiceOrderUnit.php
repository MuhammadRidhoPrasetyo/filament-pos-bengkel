<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrderUnit extends Model
{
    use HasUuids;

    protected $fillable = [
        'service_order_id',
        'plate_number',
        'brand',
        'model',
        'color',
        'status',
        'checkin_at',
        'completed_at',
        'complaint',
        'diagnosis',
        'work_done',
        'estimated_total',
        'plate_number',
        'brand',
        'model',
        'year',
        'color',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function mechanics()
    {
        return $this->hasMany(ServiceOrderUnitMechanic::class, 'service_order_unit_id');
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }
}
