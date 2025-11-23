<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductStock;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class CashierPage extends Component
{
    use WithPagination;

    #[Computed()]
    public function products()
    {
        return ProductStock::query()
            // ->withWhereHas('product', function ($q) {

            //         $q->whereAny(
            //             [
            //                 'sku',
            //                 'name',
            //                 'type',
            //                 'keyword',
            //                 'compatibility',
            //                 'size',
            //                 'unit',
            //                 'description',
            //             ],
            //             'LIKE',
            //             "%$this->search%"
            //         );

            // })
            ->where('store_id', Auth::user()->store_id)
            ->simplePaginate(12);
    }

    public function render()
    {
        return view('livewire.cashier-page');
    }
}
