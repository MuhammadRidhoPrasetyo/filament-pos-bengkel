<?php

namespace App\Filament\Pages;

use UnitEnum;
use BackedEnum;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Payment;
use App\Models\Supplier;
use Filament\Pages\Page;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\DiscountType;
use App\Models\ProductStock;
use App\Models\ServiceOrder;
use Livewire\WithPagination;
use App\Models\ProductCategory;
use App\Models\ProductDiscount;
use App\Models\ProductMovement;
use App\Models\TransactionItem;
use Filament\Support\Enums\Width;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class Cashier extends Page
{
    use WithPagination, WithoutUrlPagination;

    protected string $view = 'filament.pages.cashier';
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedWallet;
    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Kasir';
    protected static ?string $modelLabel = 'Kasir';
    protected static ?string $pluralModelLabel = 'Kasir';
    protected static ?string $slug = 'cashier';
    public string|null $productCategoryId = null;
    public string|null $brandId = null;
    public string $search = '';
    public array $carts = [];

    public $activeTab = 'carts';

    public ?int $selectedDiscountTypeId = null; // jenis diskon yg dipilih di kasir (P1, P2, dst)
    public array $discountTypeOptions = [];     // untuk <select>

    public float $amountPaid = 0.0;
    public ?string $universalDiscountMode = null; // 'percent'|'amount'
    public float $universalDiscountValue  = 0.0;

    public ?string $customerId = null;
    public array $customerOptions = [];   // [id => name]

    public ?string $paymentId = null;
    public array $paymentOptions = [];    // [id => name]

    // Store aktif
    public ?Store $activeStore = null;
    public ?string $activeStoreId = null;

    // Mode checkout: normal / dari service order
    public string $checkoutMode = 'normal';

    public ?string $serviceOrderId = null;
    public array $serviceOrderOptions = []; // [id => number]

    public function mount(): void
    {
        // Tentukan store aktif (sementara ambil pertama;
        // nanti bisa dikaitkan dengan user / role)
        $this->activeStore = Auth::user()->store;
        $this->activeStoreId = $this->activeStore?->id;

        // Dropdown jenis diskon (P1, P2, ...)
        $this->discountTypeOptions = DiscountType::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        // Dropdown customer
        $this->customerOptions = Supplier::query()
            ->where('type', 'customer')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        // Dropdown payment method
        $this->paymentOptions = Payment::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function loadServiceOrderOptions(): void
    {
        if (! $this->activeStoreId) {
            $this->serviceOrderOptions = [];
            return;
        }

        $this->serviceOrderOptions = ServiceOrder::query()
            ->where('store_id', $this->activeStoreId)
            ->whereNull('transaction_id')               // belum dibuat invoice
            ->whereIn('status', ['checkin', 'in_progress', 'ready'])
            ->orderByDesc('checkin_at')
            ->limit(50)
            ->pluck('number', 'id')
            ->toArray();
    }

    public function updatedServiceOrderId($value): void
    {
        $this->resetCart();

        if (! $value) {
            return;
        }

        $serviceOrder = ServiceOrder::query()
            ->with([
                'store',
                'customer',
                'units.items.product.productCategory',
            ])
            ->find($value);

        if (! $serviceOrder) {
            return;
        }

        // Sync header dari SO
        $this->activeStoreId = $serviceOrder->store_id;
        $this->activeStore   = $serviceOrder->store;
        $this->customerId    = $serviceOrder->customer_id;

        foreach ($serviceOrder->units as $unit) {
            foreach ($unit->items as $soItem) {
                $product   = $soItem->product;                // bisa null kalau jasa custom
                $category  = $product?->productCategory;
                $pricingMode = $category?->pricing_mode ?? 'fixed';

                $productStock = null;
                if ($product) {
                    $productStock = ProductStock::query()
                        ->where('product_id', $product->id)
                        ->where('store_id', $serviceOrder->store_id)
                        ->first();
                }

                $qty          = (int) $soItem->quantity;
                $sellingPrice = (float) $soItem->unit_price;      // di SO kamu simpan harga/unit
                $lineTotal    = (float) $soItem->line_total ?: ($qty * $sellingPrice);

                $purchasePrice = $productStock?->productPrice?->purchase_price ?? 0;
                $markup        = $product?->markup ?? 0;

                $maxStock = $productStock?->quantity ?? $qty; // labor biasanya 0 → bebas saja

                $this->carts[] = [
                    'product_stock_id' => $productStock?->id,
                    'store_id'         => $serviceOrder->store_id,
                    'product_id'       => $product?->id,

                    'product_name'     => $soItem->description ?: ($product?->name ?? '-'),
                    'price_type'       => $product->price_type ?? 'toko',

                    'quantity'         => $qty,
                    'max_quantity'     => $maxStock,

                    'purchase_price'   => $purchasePrice,
                    'markup'           => $markup,

                    'pricing_mode'     => $pricingMode,
                    'selling_price'    => $sellingPrice,
                    'final_unit_price' => $sellingPrice,

                    'discount_type_id'    => null,
                    'discount_label'      => null,
                    'discount_mode'       => null,
                    'discount_value'      => 0,
                    'discount_amount'     => 0,
                    'manual_discount_off' => false,

                    // flag referensi ke SO (kalau mau dipakai nanti)
                    'service_order_id'      => $serviceOrder->id,
                    'service_order_unit_id' => $unit->id,
                    'service_order_item_id' => $soItem->id,
                ];
            }
        }
    }

    public function updatedCheckoutMode($value): void
    {
        // setiap pindah mode, kosongkan keranjang & SO terpilih
        $this->resetCart();
        $this->serviceOrderId = null;

        if ($value === 'service') {
            $this->loadServiceOrderOptions();
        }
    }

    public function incrementQuantity(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        $currentQty = (int) $this->carts[$index]['quantity'];
        $maxQty     = (int) ($this->carts[$index]['max_quantity'] ?? 0);

        if ($maxQty > 0 && $currentQty >= $maxQty) {
            Notification::make()
                ->title('Tidak dapat menambah, stok hanya ' . $maxQty . ' item.')
                ->warning()
                ->send();
            return;
        }

        $this->carts[$index]['quantity']++;
    }

    public function decrementQuantity(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        if ($this->carts[$index]['quantity'] > 1) {
            $this->carts[$index]['quantity']--;
        } else {
            // kalau qty sudah 1 dan dikurangi, hapus item dari cart
            $this->removeFromCart($index);
        }
    }

    public function removeFromCart(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        unset($this->carts[$index]);
        // reset index array biar rapi
        $this->carts = array_values($this->carts);
    }

    public function addToCart(ProductStock $productStock): void
    {
        $max = (int) $productStock->quantity;
        if ($max <= 0) {

            Notification::make()
                ->title('Stok barang ini habis.')
                ->error()
                ->send();

            return;
        }

        $product      = $productStock->product;
        $category     = $product->productCategory;
        $pricingMode  = $category?->pricing_mode ?? 'fixed'; // 'fixed' / 'editable'
        $isEditable   = $pricingMode === 'editable';
        // dd($pricingMode);
        $sellingPrice = $productStock?->productPrice?->selling_price ?? 0;

        $this->carts[] = [
            'product_stock_id' => $productStock->id,
            'store_id'         => $productStock->store_id,
            'product_id'       => $productStock->product_id,

            'product_name'     => $product->name,
            'price_type'       => $product->price_type ?? 'toko',

            'quantity'         => 1,
            'max_quantity'     => $max,

            'purchase_price'   => $productStock?->productPrice?->purchase_price ?? 0,
            'markup'           => $product->markup ?? 0,

            'pricing_mode'     => $pricingMode,     // <<– penting
            'selling_price'    => $sellingPrice,
            'final_unit_price' => $sellingPrice,

            // diskon (kalau sudah ada struktur ini)
            'discount_type_id'    => null,
            'discount_label'      => null,
            'discount_mode'       => null,
            'discount_value'      => 0,
            'discount_amount'     => 0,
            'manual_discount_off' => false,
        ];
    }

    public function updateUnitPrice(int $index, $value): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        // hanya boleh edit jika pricing_mode = editable
        $mode = $this->carts[$index]['pricing_mode'] ?? 'fixed';
        if ($mode !== 'editable') {
            return;
        }

        $price = (float) ($value ?? 0);
        if ($price < 0) {
            $price = 0;
        }

        $this->carts[$index]['selling_price'] = $price;

        // Kalau ada diskon aktif dan item tidak di-force off, pakai base price baru
        if (!empty($this->carts[$index]['manual_discount_off']) || empty($this->selectedDiscountTypeId)) {
            // tanpa diskon / diskon dimatikan → final = selling_price
            $this->carts[$index]['discount_amount']  = 0;
            $this->carts[$index]['final_unit_price'] = $price;
        } else {
            // re-apply rule diskon dengan harga baru
            $this->applyDiscountToCartItem($index);
        }
    }

    public function updatedSelectedDiscountTypeId($value): void
    {
        foreach (array_keys($this->carts) as $index) {
            // kalau user mematikan diskon di item ini, jangan apply lagi
            if (!empty($this->carts[$index]['manual_discount_off'])) {
                continue;
            }

            $this->applyDiscountToCartItem($index);
        }
    }

    protected function applyDiscountToCartItem(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        $cart = $this->carts[$index];

        $qty          = (int) ($cart['quantity'] ?? 1);
        $sellingPrice = (float) ($cart['selling_price'] ?? 0);

        // default: tanpa diskon
        if (empty($this->selectedDiscountTypeId)) {
            $this->carts[$index]['discount_type_id'] = null;
            $this->carts[$index]['discount_label']   = null;
            $this->carts[$index]['discount_mode']    = null;
            $this->carts[$index]['discount_value']   = 0;
            $this->carts[$index]['discount_amount']  = 0;
            $this->carts[$index]['final_unit_price'] = $sellingPrice;
            return;
        }

        // cari rule diskon untuk product + store + discount_type
        $rule = ProductDiscount::query()
            ->where('product_id', $cart['product_id'])
            ->where('store_id',   $cart['store_id'])
            ->where('discount_type_id', $this->selectedDiscountTypeId)
            ->first();

        if (! $rule) {
            // tidak ada rule: anggap tidak kena diskon
            $this->carts[$index]['discount_type_id'] = null;
            $this->carts[$index]['discount_label']   = null;
            $this->carts[$index]['discount_mode']    = null;
            $this->carts[$index]['discount_value']   = 0;
            $this->carts[$index]['discount_amount']  = 0;
            $this->carts[$index]['final_unit_price'] = $sellingPrice;
            return;
        }

        // hitung potongan per unit
        $perUnitDiscount = 0;

        if ($rule->type === 'percent') {
            $perUnitDiscount = $sellingPrice * ((float) $rule->value / 100);
        } elseif ($rule->type === 'amount') {
            $perUnitDiscount = (float) $rule->value;
        }

        // jangan sampai lebih besar dari harga
        $perUnitDiscount = max(0, min($perUnitDiscount, $sellingPrice));

        $finalUnitPrice = $sellingPrice - $perUnitDiscount;
        $lineDiscount   = $perUnitDiscount * $qty;

        $discountTypeName = $rule->discountType->name ?? 'Diskon'; // relasi ke DiscountType

        $this->carts[$index]['discount_type_id'] = $this->selectedDiscountTypeId;
        $this->carts[$index]['discount_label']   = $discountTypeName; // misal P1
        $this->carts[$index]['discount_mode']    = $rule->type;
        $this->carts[$index]['discount_value']   = $rule->value;
        $this->carts[$index]['discount_amount']  = $lineDiscount;
        $this->carts[$index]['final_unit_price'] = $finalUnitPrice;
    }

    public function updateQuantity(int $index, $value): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        $qty = (int) ($value ?? 1);
        if ($qty < 1) {
            $qty = 1;
        }

        $max = (int) ($this->carts[$index]['max_quantity'] ?? 0);
        if ($max > 0 && $qty > $max) {
            $qty = $max;
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'warning',
                'message' => 'Tidak dapat melebihi stok. Maksimal ' . $max . ' item.',
            ]);
        }

        $this->carts[$index]['quantity'] = $qty;

        // setelah qty berubah, hitung ulang diskon utk baris ini
        $this->recalculateDiscountForItem($index);
    }

    protected function recalculateDiscountForItem(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        // jika diskon dimatikan manual utk item ini, final_unit_price = sellingPrice
        if (!empty($this->carts[$index]['manual_discount_off'])) {
            $sellingPrice = (float) ($this->carts[$index]['selling_price'] ?? 0);
            $this->carts[$index]['discount_amount']  = 0;
            $this->carts[$index]['final_unit_price'] = $sellingPrice;
            return;
        }

        if ($this->selectedDiscountTypeId) {
            $this->applyDiscountToCartItem($index);
        } else {
            // tidak ada diskon global
            $sellingPrice = (float) ($this->carts[$index]['selling_price'] ?? 0);
            $this->carts[$index]['discount_amount']  = 0;
            $this->carts[$index]['final_unit_price'] = $sellingPrice;
        }
    }

    public function removeDiscountForItem(int $index): void
    {
        if (! isset($this->carts[$index])) {
            return;
        }

        $sellingPrice = (float) ($this->carts[$index]['selling_price'] ?? 0);

        $this->carts[$index]['manual_discount_off'] = true;
        $this->carts[$index]['discount_type_id']    = null;
        $this->carts[$index]['discount_label']      = null;
        $this->carts[$index]['discount_mode']       = null;
        $this->carts[$index]['discount_value']      = 0;
        $this->carts[$index]['discount_amount']     = 0;
        $this->carts[$index]['final_unit_price']    = $sellingPrice;
    }

    // Total kotor: sebelum diskon apa pun
    public function getSubtotalProperty(): float
    {
        return collect($this->carts)->sum(function ($row) {
            $qty   = (int) ($row['quantity'] ?? 0);
            $price = (float) ($row['selling_price'] ?? 0); // harga dasar per unit
            return $qty * $price;
        });
    }

    // Total diskon per item (P1, P2, dst) – nominal
    public function getItemDiscountTotalProperty(): float
    {
        return collect($this->carts)->sum(function ($row) {
            return (float) ($row['discount_amount'] ?? 0);
        });
    }

    // Subtotal setelah diskon per item, sebelum diskon universal
    public function getSubtotalAfterItemDiscountProperty(): float
    {
        $subtotal          = $this->subtotal;          // panggil computed subtotal di atas
        $itemDiscountTotal = $this->itemDiscountTotal; // panggil computed itemDiscountTotal

        $value = $subtotal - $itemDiscountTotal;

        return $value > 0 ? $value : 0.0;
    }

    // Kalau belum pakai pajak, buat saja 0 dulu
    public function getTaxTotalProperty(): float
    {
        return 0.0;
    }

    public function getUniversalDiscountAmountProperty(): float
    {
        $base = $this->subtotalAfterItemDiscount; // subtotal setelah diskon per item

        if (! $this->universalDiscountMode || $this->universalDiscountValue <= 0) {
            return 0.0;
        }

        if ($this->universalDiscountMode === 'percent') {
            $amount = $base * ($this->universalDiscountValue / 100);
        } else { // 'amount'
            $amount = $this->universalDiscountValue;
        }

        // jangan lebih besar dari base
        $amount = max(0, min($base, $amount));

        return $amount;
    }

    public function getChangeAmountProperty(): float
    {
        $change = $this->amountPaid - $this->grandTotal;

        return $change > 0 ? $change : 0.0;
    }

    public function getGrandTotalProperty(): float
    {
        $base   = $this->subtotalAfterItemDiscount;
        $disc   = $this->universalDiscountAmount; // computed di atas
        $tax    = $this->taxTotal;                // sementara 0

        $grand = $base - $disc + $tax;

        return $grand > 0 ? $grand : 0.0;
    }

    /* ==========================================================
    |  CHECKOUT
    |==========================================================*/

    public function checkout(): void
    {
        if (empty($this->carts)) {
            Notification::make()
                ->danger()
                ->title('Keranjang masih kosong')
                ->body('Silahkan isi keranjang terlebih dahulu.')
                ->send();

            return;
        }

        if (! $this->activeStoreId) {

            Notification::make()
                ->danger()
                ->title('Toko belum dipilih')
                ->body('Silahkan pilih toko terlebih dahulu.')
                ->send();

            return;
        }

        $cashierId = Auth::id();
        $store = Store::query()
            ->where('id', $this->activeStoreId)
            ->lockForUpdate()
            ->firstOrFail();

        DB::transaction(function () use ($cashierId, $store) {
            $subtotal          = $this->subtotal;
            $itemDiscountTotal = $this->itemDiscountTotal;
            $subtotalAfterItem = $this->subtotalAfterItemDiscount;
            $universalAmount   = $this->universalDiscountAmount;
            $taxTotal          = $this->taxTotal;
            $grandTotal        = $this->grandTotal;

            $paidAmount   = $this->amountPaid;
            $changeAmount = $this->changeAmount;

            $paymentStatus = 'unpaid';
            if ($paidAmount >= $grandTotal && $grandTotal > 0) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0 && $paidAmount < $grandTotal) {
                $paymentStatus = 'partial';
            }

            // Hitung total cost & profit
            $totalCost   = 0.0;
            $totalProfit = 0.0;

            foreach ($this->carts as $cart) {
                $qty        = (int) ($cart['quantity'] ?? 0);
                $unitCost   = (float) ($cart['purchase_price'] ?? 0);
                $finalPrice = (float) ($cart['final_unit_price'] ?? $cart['selling_price'] ?? 0);

                $lineCost   = $qty * $unitCost;
                $lineTotal  = $qty * $finalPrice;
                $lineProfit = $lineTotal - $lineCost;

                $totalCost   += $lineCost;
                $totalProfit += $lineProfit;
            }

            // Simpan header transaksi
            $transaction = Transaction::create([
                'number'                        => $store->generateNextReceiptNumber(),
                'store_id'                      => $this->activeStoreId,
                'user_id'                    => $cashierId,
                'customer_id'                   => $this->customerId,
                'payment_id'                    => $this->paymentId,
                'transaction_date'              => now(),

                'subtotal'                      => $subtotal,
                'item_discount_total'           => $itemDiscountTotal,
                'subtotal_after_item_discount'  => $subtotalAfterItem,

                'universal_discount_mode'       => $this->universalDiscountMode,
                'universal_discount_value'      => $this->universalDiscountValue ?: null,
                'universal_discount_amount'     => $universalAmount,

                'tax_total'                     => $taxTotal,
                'grand_total'                   => $grandTotal,

                'paid_amount'                   => $paidAmount,
                'change_amount'                 => $changeAmount,
                'payment_status'                => $paymentStatus,

                'total_cost'                    => $totalCost,
                'total_profit'                  => $totalProfit,

                'status'                        => 'completed',
                'note'                          => null,

                'type'             => $this->checkoutMode === 'service' ? 'service' : 'retail',
                'service_order_id' => $this->checkoutMode === 'service' ? $this->serviceOrderId : null,
            ]);

            // Simpan detail items + update stok + movement
            foreach ($this->carts as $cart) {
                $qty          = (int) ($cart['quantity'] ?? 0);
                $unitPrice    = (float) ($cart['selling_price'] ?? 0);
                $finalUnit    = (float) ($cart['final_unit_price'] ?? $unitPrice);
                $unitCost     = (float) ($cart['purchase_price'] ?? 0);

                $lineSubtotal = $qty * $unitPrice;
                $lineTotal    = $qty * $finalUnit;

                $perUnitDisc  = $unitPrice - $finalUnit;
                $lineDiscAmt  = $perUnitDisc * $qty;

                $lineCostTotal = $qty * $unitCost;
                $lineProfit    = $lineTotal - $lineCostTotal;

                $item = TransactionItem::create([
                    'transaction_id'       => $transaction->id,
                    'product_id'           => $cart['product_id'],
                    'store_id'             => $cart['store_id'],
                    'product_stock_id'     => $cart['product_stock_id'] ?? null,

                    'quantity'             => $qty,

                    'unit_price'           => $unitPrice,
                    'item_discount_mode'   => $cart['discount_mode'] ?? null,
                    'item_discount_value'  => $cart['discount_value'] ?? null,
                    'item_discount_amount' => $lineDiscAmt,
                    'final_unit_price'     => $finalUnit,

                    'line_subtotal'        => $lineSubtotal,
                    'line_total'           => $lineTotal,
                    'discount_type_id'     => $cart['discount_type_id'] ?? null,

                    'unit_cost'            => $unitCost,
                    'line_cost_total'      => $lineCostTotal,
                    'line_profit'          => $lineProfit,

                    'price_edited'         => ($cart['pricing_mode'] ?? 'fixed') === 'editable',
                    'pricing_mode'         => $cart['pricing_mode'] ?? null,
                ]);
            }

            if ($this->checkoutMode === 'service' && $this->serviceOrderId) {
                $so = ServiceOrder::find($this->serviceOrderId);
                if ($so) {
                    $so->update([
                        'status'        => 'invoiced',
                        'transaction_id' => $transaction->id,
                    ]);
                    $so->units()->update([
                        'status'       => 'invoiced',
                        'completed_at' => now(),
                    ]);
                }
            }
        });

        Notification::make()
            ->title('Transaksi berhasil disimpan')
            ->success()
            ->send();

        $this->resetCart();
    }

    protected function resetCart()
    {
        $this->reset([
            'carts'
        ]);
    }

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

    #[Computed()]
    public function products()
    {
        return ProductStock::query()
            ->when($this->productCategoryId, function ($q) {
                $q->whereRelation('product.productCategory', 'id', $this->productCategoryId);
            })
            ->when($this->brandId, function ($q) {
                $q->whereRelation('product.brand', 'id', $this->brandId);
            })
            ->whereNotIn('product_id', collect($this->carts)->pluck('product_id')->toArray())
            ->withWhereHas('product', function ($q) {
                $q->whereAny(
                    [
                        'sku',
                        'name',
                        'type',
                        'keyword',
                        'compatibility',
                        'size',
                        'description',
                    ],
                    'LIKE',
                    "%$this->search%"
                );
            })
            ->where('store_id', Auth::user()->store_id)
            ->simplePaginate(12);
    }
}
