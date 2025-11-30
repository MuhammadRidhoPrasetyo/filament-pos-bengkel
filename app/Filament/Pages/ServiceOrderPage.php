<?php

namespace App\Filament\Pages;

use BackedEnum;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Supplier;
use Filament\Pages\Page;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\ServiceOrder;
use App\Models\CustomerVehicle;
use App\Models\TransactionItem;
use App\Models\ServiceOrderItem;
use Illuminate\Support\Facades\DB;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use App\Filament\Clusters\Services\ServicesCluster;
use CodeWithDennis\FilamentLucideIcons\Enums\LucideIcon;

class ServiceOrderPage extends Page
{
    protected string $view = 'filament.pages.service-order-page';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 1;
    protected static ?string $cluster = ServicesCluster::class;
    protected static ?string $navigationLabel = 'Buat Servis';
    protected static ?string $modelLabel = 'Buat Servis';
    protected static ?string $pluralModelLabel = 'Buat Servis';
    protected static ?string $title           = 'Buat Service Order';

    // =======================
    // STATE FORM
    // =======================
    public ?string $storeId    = null;
    public ?string $customerId = null;

    /** @var array<int, array> */
    public array $units = [
        // Contoh struktur:
        // [
        //   'vehicle_id'   => null,
        //   'complaint'    => '',
        //   'mechanic_ids' => [],
        //   'items'        => [
        //      ['item_type' => 'part', 'product_id' => null, 'description' => '', 'qty' => 1, 'unit_price' => 0, 'line_total' => 0],
        //   ],
        // ]
    ];

    public bool $createInvoiceImmediately = true;

    // dropdown data
    public array $stores      = [];
    public array $customers   = [];
    public array $mechanics   = [];
    public array $products    = [];
    public array $vehicles    = [];

    // form kendaraan baru per unit
    public array $newVehicle = [
        // diisi dinamis per unit
        // $uIndex => ['plate_number' => '', 'brand' => '', ...]
    ];


    public function mount(): void
    {
        $this->stores    = Store::query()->pluck('name', 'id')->toArray();
        $this->customers = Supplier::query()->pluck('name', 'id')->toArray();
        $this->mechanics = User::query()
            ->whereHas('roles', fn($q) => $q->where('name', 'mechanic'))
            ->pluck('name', 'id')
            ->toArray(); // sesuaikan
        $this->products  = Product::query()->pluck('name', 'id')->toArray();

        $this->units = [
            [
                // DATA KENDARAAN
                'plate_number' => '',
                'brand'        => '',
                'model'        => '',
                'color'        => '',

                // DATA SERVIS
                'complaint'     => '',
                'diagnosis'     => '',
                'work_done'     => '',

                // STATUS & WAKTU
                'status'        => 'checking',      // default aman
                'checkin_at'    => now()->format('Y-m-d H:i:s'),
                'completed_at'  => null,

                // ESTIMASI TOTAL
                'estimated_total' => 0,

                // MEKANIK
                'mechanic_ids' => [],

                // ITEM (barang/layanan)
                'items'        => [],
            ],
        ];
    }

    public function updatedCustomerId($value): void
    {
        // tiap kali ganti customer, load kendaraan miliknya
        $this->vehicles = CustomerVehicle::query()
            ->where('customer_id', $value)
            ->orderBy('plate_number')
            ->get()
            ->mapWithKeys(fn($v) => [$v->id => "{$v->plate_number} - {$v->brand} {$v->model}"])
            ->toArray();

        // reset pilihan vehicle di semua unit karena customer berubah
        foreach ($this->units as $idx => $unit) {
            $this->units[$idx]['vehicle_id'] = null;
        }
    }

    public function addUnit(): void
    {
        $this->units[] = [
            'plate_number' => '',
            'brand'        => '',
            'model'        => '',
            'color'        => '',

            // DATA SERVIS
            'complaint'     => '',
            'diagnosis'     => '',
            'work_done'     => '',

            // STATUS & WAKTU
            'status'        => 'checking',      // default aman
            'checkin_at'    => now()->format('Y-m-d H:i:s'),
            'completed_at'  => null,

            // ESTIMASI TOTAL
            'estimated_total' => 0,

            // MEKANIK
            'mechanic_ids' => [],

            // ITEM (barang/layanan)
            'items'        => [],
        ];
    }

    public function removeUnit(int $index): void
    {
        unset($this->units[$index]);
        $this->units = array_values($this->units);
    }

    public function addItem(int $unitIndex, string $type = 'part'): void
    {
        $this->units[$unitIndex]['items'][] = [
            'item_type'     => $type,
            'product_id'    => null,
            'description'   => '',
            'qty'           => 1,
            'unit_price'    => 0,
            'line_total'    => 0,
            'pricing_mode'  => null, // <-- penting
        ];
    }

    public function removeItem(int $unitIndex, int $itemIndex): void
    {
        unset($this->units[$unitIndex]['items'][$itemIndex]);
        $this->units[$unitIndex]['items'] = array_values($this->units[$unitIndex]['items']);
    }

    public function updatedUnits($value, $name): void
    {
        $parts = explode('.', $name);

        // struktur: units.{uIndex}.items.{iIndex}.field
        if (count($parts) >= 4 && $parts[1] === 'items') {
            $unitIndex = (int) $parts[0];
            $itemIndex = (int) $parts[2];
            $field     = $parts[3] ?? null;

            if ($field === 'product_id') {
                $this->hydrateItemFromProduct($unitIndex, $itemIndex);
            }

            if (in_array($field, ['qty', 'unit_price'], true)) {
                $this->recalculateItemLine($unitIndex, $itemIndex);
            }
        }
    }

    protected function recalculateItemLine(int $unitIndex, int $itemIndex): void
    {
        if (! isset($this->units[$unitIndex]['items'][$itemIndex])) {
            return;
        }

        $item = $this->units[$unitIndex]['items'][$itemIndex];

        $qty       = (int) ($item['qty'] ?? 0);
        $unitPrice = (float) ($item['unit_price'] ?? 0);

        $this->units[$unitIndex]['items'][$itemIndex]['line_total'] = $qty * $unitPrice;
    }

    protected function hydrateItemFromProduct(int $unitIndex, int $itemIndex): void
    {
        if (! isset($this->units[$unitIndex]['items'][$itemIndex])) {
            return;
        }

        $item      = &$this->units[$unitIndex]['items'][$itemIndex];
        $productId = $item['product_id'] ?? null;

        if (! $productId) {
            // reset kalau produk dikosongkan
            $item['unit_price']   = 0;
            $item['pricing_mode'] = null;
            $this->recalculateItemLine($unitIndex, $itemIndex);

            return;
        }

        // ambil product + category + harga aktif untuk store ini
        $product = Product::query()
            ->with([
                'productCategory',
                'prices' => fn($q) => $q
                    ->where('store_id', $this->storeId)
                    ->where('is_active', true),
            ])
            ->find($productId);

        if (! $product) {
            return;
        }
        $pricingMode = $product->productCategory->pricing_mode ?? 'fixed';

        // ambil selling_price aktif, kalau tidak ada jadikan 0
        $activePrice = optional($product->prices->first())->selling_price ?? 0;
        // dd($product->prices);

        $item['pricing_mode'] = $pricingMode;

        // aturan:
        // - kalau pricing_mode fixed → selalu pakai harga dari DB
        // - kalau editable:
        //   - kalau unit_price masih 0 → isi default dari DB
        //   - kalau sudah pernah diisi manual, biarkan (kasir/frontdesk boleh set sendiri)
        if ($pricingMode === 'fixed') {
            $item['unit_price'] = $activePrice;
        } else {
            if (empty($item['unit_price'])) {
                $item['unit_price'] = $activePrice;
            }
        }

        $this->recalculateItemLine($unitIndex, $itemIndex);
    }

    // public function initNewVehicleForm(int $unitIndex): void
    // {
    //     $this->newVehicle[$unitIndex] = [
    //         'plate_number' => '',
    //         'brand'        => '',
    //         'model'        => '',
    //         'year'         => null,
    //         'color'        => '',
    //         'notes'        => '',
    //     ];
    // }

    // public function saveNewVehicle(int $unitIndex): void
    // {
    //     if (! $this->customerId) {
    //         $this->addError('customerId', 'Pelanggan harus dipilih dulu sebelum menambah kendaraan.');
    //         return;
    //     }

    //     $data = $this->newVehicle[$unitIndex] ?? null;

    //     if (! $data) {
    //         return;
    //     }

    //     $this->validate([
    //         "newVehicle.$unitIndex.plate_number" => ['required', 'string'],
    //     ], [], [
    //         "newVehicle.$unitIndex.plate_number" => 'Nomor polisi',
    //     ]);

    //     $vehicle = CustomerVehicle::create([
    //         'customer_id'  => $this->customerId,
    //         'plate_number' => $data['plate_number'],
    //         'brand'        => $data['brand'] ?? null,
    //         'model'        => $data['model'] ?? null,
    //         'year'         => $data['year'] ?? null,
    //         'color'        => $data['color'] ?? null,
    //         'notes'        => $data['notes'] ?? null,
    //     ]);

    //     // refresh list kendaraan
    //     $this->updatedCustomerId($this->customerId);

    //     // set kendaraan ini ke unit terkait
    //     $this->units[$unitIndex]['vehicle_id'] = $vehicle->id;

    //     // bersihkan form kendaraan baru
    //     unset($this->newVehicle[$unitIndex]);
    // }

    public function save(bool $withInvoice = false)
    {
        try {
            $this->validate([
                'storeId'            => ['required', 'exists:stores,id'],
                'customerId'         => ['required', 'exists:suppliers,id'],
                'units'              => ['required', 'array', 'min:1'],
                'units.*.plate_number' => ['required', 'string'],
                'units.*.mechanic_ids' => ['array', 'min:1'],
                'units.*.items'        => ['array', 'min:1'],
            ], [], [
                'storeId'               => 'toko',
                'customerId'            => 'pelanggan',
                'units'                 => 'unit motor',
                'units.*.plate_number'  => 'nomor polisi',
                'units.*.mechanic_ids'  => 'mekanik',
                'units.*.items'         => 'item',
            ]);

            $serviceOrder = DB::transaction(function () use ($withInvoice) {
                $number = 'SO-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));

                $so = ServiceOrder::create([
                    'number'           => $number,
                    'store_id'         => $this->storeId,
                    'customer_id'      => $this->customerId,
                    'status'           => 'checkin',
                    'checkin_at'       => now(),
                    'general_complaint' => null,
                    'estimated_total'  => 0,
                ]);

                $grandEstimated = 0;

                foreach ($this->units as $unitData) {
                    $unit = $so->units()->create([
                        'plate_number' => $unitData['plate_number'],
                        'brand'        => $unitData['brand'] ?: null,
                        'model'        => $unitData['model'] ?: null,
                        'color'        => $unitData['color'] ?: null,
                        'status'       => 'checkin',
                        'checkin_at'   => now(),
                        'complaint'    => $unitData['complaint'] ?: null,
                        'diagnosis'    => $unitData['diagnosis'] ?: null,
                        'work_done'    => $unitData['work_done'] ?: null,
                        'estimated_total' => 0,
                    ]);

                    $mechanicIds = collect($unitData['mechanic_ids'])
                        ->mapWithKeys(function ($mechanic) {
                            return [
                                'mechanic_id' => $mechanic
                            ];
                        })
                        ->toArray();

                    // dd($mechanicIds);
                    $unit->mechanics()->create($mechanicIds);

                    $unitTotal = 0;

                    foreach ($unitData['items'] as $row) {
                        $qty   = (int) ($row['qty'] ?? 0);
                        $price = (float) ($row['unit_price'] ?? 0);

                        if ($qty <= 0 || $price < 0) {
                            continue;
                        }

                        $lineTotal = $qty * $price;

                        ServiceOrderItem::create([
                            'service_order_unit_id' => $unit->id,
                            'item_type'             => $row['item_type'],
                            'product_id'            => $row['product_id'] ?: null,
                            'description'           => $row['description'] ?: null,
                            'quantity'              => $qty,
                            'unit_price'            => $price,
                            'line_total'            => $lineTotal,
                        ]);

                        $unitTotal += $lineTotal;
                    }

                    $unit->update([
                        'estimated_total' => $unitTotal,
                    ]);

                    $grandEstimated += $unitTotal;
                }

                $so->update([
                    'estimated_total' => $grandEstimated,
                ]);

                if ($withInvoice) {
                    $this->createTransactionFromServiceOrder($so);
                }

                return $so;
            });

            \Filament\Notifications\Notification::make()
                ->title('Service Order ' . $serviceOrder->number . ' berhasil dibuat.')
                ->success()
                ->send();

            return redirect()->route('filament.admin.pages.service-order-wizard');
        } catch (\Throwable $e) {
            report($e);

            \Filament\Notifications\Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        } catch (\Exception $e) {
            report($e);

            \Filament\Notifications\Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function createTransactionFromServiceOrder(ServiceOrder $so): void
    {
        $so->load(['units.items']);

        $storeId    = $so->store_id;
        $customerId = $so->customer_id;

        $subtotal = 0;
        $itemsPayload = [];

        foreach ($so->units as $unit) {
            foreach ($unit->items as $item) {
                $lineSubtotal = $item->line_total;

                $itemsPayload[] = [
                    'product_id'          => $item->product_id,
                    'store_id'            => $storeId,
                    'quantity'            => $item->quantity,
                    'unit_price'          => $item->unit_price,
                    'item_discount_mode'  => null,
                    'item_discount_value' => null,
                    'item_discount_amount' => 0,
                    'final_unit_price'    => $item->unit_price,
                    'line_subtotal'       => $item->quantity * $item->unit_price,
                    'line_total'          => $lineSubtotal,
                    'unit_cost'           => 0,
                    'line_cost_total'     => 0,
                    'line_profit'         => $lineSubtotal,
                    'pricing_mode'        => 'fixed',
                    'price_edited'        => false,
                ];

                $subtotal += $lineSubtotal;
            }
        }

        $trx = Transaction::create([
            'id'                          => (string) Str::uuid(),
            'number'                      => 'POS-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
            'store_id'                    => $storeId,
            'user_id'                     => auth()->id(),
            'customer_id'                 => $customerId,
            'payment_id'                  => null,
            'transaction_date'            => now(),
            'subtotal'                    => $subtotal,
            'item_discount_total'         => 0,
            'subtotal_after_item_discount' => $subtotal,
            'universal_discount_mode'     => null,
            'universal_discount_value'    => null,
            'universal_discount_amount'   => 0,
            'tax_total'                   => 0,
            'grand_total'                 => $subtotal,
            'paid_amount'                 => 0,
            'change_amount'               => 0,
            'payment_status'              => 'unpaid',
            'total_cost'                  => 0,
            'total_profit'                => $subtotal,
            'status'                      => 'draft',
            'note'                        => 'Auto dari Service Order ' . $so->number,
            'type'                        => 'service',
            'service_order_id'            => $so->id,
        ]);

        foreach ($itemsPayload as $row) {
            $trxItem = new TransactionItem($row);
            $trxItem->transaction_id = $trx->id;
            $trxItem->save();
        }

        $so->update([
            'status'        => 'invoiced',
            'transaction_id' => $trx->id,
        ]);
    }
}
