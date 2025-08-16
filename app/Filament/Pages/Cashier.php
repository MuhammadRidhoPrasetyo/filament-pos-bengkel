<?php

namespace App\Filament\Pages;

use App\Models\Brand;
use App\Models\ProductCategory;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Cashier extends Page
{
    protected string $view = 'filament.pages.cashier';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedWallet;
    protected static ?string $slug = 'cashier';
    public string $productCategoryId;
    public string $brandId;

    #[Computed()]
    public function productCategories()
    {
        return ProductCategory::all()
            ->pluck('name', 'id');
    }

    #[Computed()]
    public function brands()
    {
        return Brand::all()
            ->pluck('name', 'id');
    }
}
