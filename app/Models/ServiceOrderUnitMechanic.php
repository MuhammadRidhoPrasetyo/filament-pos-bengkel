<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ServiceOrderUnitMechanic extends Model
{
    use HasUuids;

    protected $fillable = [
        'service_order_unit_id',
        'mechanic_id',
        'role',
        'work_portion',
    ];

    public function serviceOrderUnit()
    {
        return $this->belongsTo(ServiceOrderUnit::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class);
    }
}
