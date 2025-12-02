<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function mechanicsPivot(): HasMany
    {
        return $this->hasMany(ServiceOrderUnitMechanic::class);
    }

    // 2) Relasi ke User (untuk Select Filament)
    public function mechanics(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'service_order_unit_mechanics',
            'service_order_unit_id',
            'mechanic_id'
        )
            ->using(ServiceOrderUnitMechanic::class)
            ->withPivot(['role', 'work_portion'])
            ->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }
}
