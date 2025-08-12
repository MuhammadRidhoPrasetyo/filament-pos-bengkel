<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\ProductPrice;
use App\Models\ProductStock;
use App\Models\ProductDiscount;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Products\ProductResource;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // protected function afterCreate(): void
    // {
    //     $data = $this->form->getState();
    //     try {

    //         $productPrice = ProductPrice::create([
    //             'product_id'      => $this->record->id, // <— product_id dari record yang baru dibuat
    //             'store_id'        => $data['store_id'],
    //             'price_type'      => $data['price_type'],
    //             'purchase_price'  => $data['purchase_price'],
    //             'selling_price'   => $data['selling_price'],
    //             'markup'          => $data['markup'],
    //         ]);

    //     } catch (\Throwable $th) {
    //         Notification::make()
    //             ->danger()
    //             ->title('Error')
    //             ->body($th->getMessage())
    //             ->persistent()
    //             ->send();
    //     }
    // }
}
