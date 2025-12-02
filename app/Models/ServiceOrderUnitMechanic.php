<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrderUnitMechanic extends Pivot
{
    protected $fillable = [
        'service_order_unit_id',
        'mechanic_id',
        'role',
        'work_portion',
    ];

    public function serviceOrderUnit()
    {
        return $this->belongsTo(ServiceOrderUnit::class, 'service_order_unit_id');
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class);
    }
}
