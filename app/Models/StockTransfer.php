<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DocumentSequence;
use App\Services\StockTransferService;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class StockTransfer extends Model
{
    use HasUuids;

    protected $fillable = [
        'from_store_id',
        'to_store_id',
        'status',
        'reference_number',
        'occurred_at',
        'created_by',
        'posted_by',
        'posted_at',
        'note',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'posted_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(StockTransferItem::class, 'stock_transfer_id');
    }

    public function fromStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toStore(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    protected static function booted()
    {
        static::creating(function (StockTransfer $transfer) {
            // Assign an auto-incrementing reference number per store & year using DocumentSequence
            $year = Carbon::now()->year;
            $storeId = $transfer->from_store_id ?? null;

            DB::transaction(function () use ($year, $storeId, $transfer) {
                $sequence = DocumentSequence::where('type', 'stock_transfer')
                    ->where('store_id', $storeId)
                    ->where('year', $year)
                    ->lockForUpdate()
                    ->first();

                if (! $sequence) {
                    $sequence = DocumentSequence::create([
                        'type' => 'stock_transfer',
                        'store_id' => $storeId,
                        'sequence' => 0,
                        'year' => $year,
                    ]);
                }

                $sequence->sequence = (int) $sequence->sequence + 1;
                $sequence->save();

                // Format: ST-{year}-{sequence padded 4}
                $transfer->reference_number = sprintf('ST-%s-%04d', $year, $sequence->sequence);
            });
        });
    }

    /**
     * Post this transfer (move stock and create movements) using the service.
     *
     * @param AuthenticatableContract|null $user
     * @return void
     */
    public function post(?AuthenticatableContract $user = null): void
    {
        $service = new StockTransferService();
        $service->post($this, $user);
    }

    /**
     * Cancel this transfer and revert stock changes if already posted.
     *
     * @param AuthenticatableContract|null $user
     * @return void
     */
    public function cancel(?AuthenticatableContract $user = null): void
    {
        $service = new StockTransferService();
        $service->cancel($this, $user);
    }
}
