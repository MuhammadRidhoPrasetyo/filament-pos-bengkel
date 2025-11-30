<?php

namespace App\Observers;

use App\Services\StockService;
use App\Models\ProductMovement;

class ProductMovementObserver
{
    public function created(ProductMovement $movement): void
    {
        // app(StockService::class)->recomputeForMovement($movement);
    }

    public function updated(ProductMovement $movement): void
    {
        app(StockService::class)->recomputeForMovement($movement);
    }

    public function deleted(ProductMovement $movement): void
    {
        app(StockService::class)->recomputeForMovement($movement);
    }
}
