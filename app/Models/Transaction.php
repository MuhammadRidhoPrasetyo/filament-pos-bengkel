<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function paymentAttempts()
    {
        return $this->hasMany(TransactionPaymentAttempt::class, 'transaction_id', 'id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Outstanding amount (grand_total - sum of payment attempts)
    public function getOutstandingAttribute(): float
    {
        // Prefer the cached `paid_amount` column (kept in sync by applyPaymentAttempt).
        // Fall back to summing payment attempts if `paid_amount` is null.
        $paid = null;
        if (array_key_exists('paid_amount', $this->attributes) && $this->paid_amount !== null) {
            $paid = (float) $this->paid_amount;
        } else {
            $paid = (float) $this->paymentAttempts()->sum('amount');
        }

        return max(0, (float) $this->grand_total - $paid);
    }

    /**
     * Apply a payment attempt: create a TransactionPaymentAttempt row and
     * atomically increment paid_amount and update payment_status.
     *
     * @param float $amount
     * @param int|null $paymentId
     * @param int|null $userId
     * @return \App\Models\TransactionPaymentAttempt|null
     */
    public function applyPaymentAttempt(float $amountGiven, ?string $paymentId = null, ?int $userId = null)
    {
        if ($amountGiven <= 0) {
            return null;
        }

        return DB::transaction(function () use ($amountGiven, $paymentId, $userId) {
            // compute outstanding based on current paid_amount (cached)
            $outstanding = max(0, (float) $this->grand_total - (float) $this->paid_amount);

            // amount to apply to invoice (cannot exceed outstanding)
            $applied = min($amountGiven, $outstanding);
            $change = $amountGiven - $applied;

            $attempt = \App\Models\TransactionPaymentAttempt::create([
                'transaction_id' => $this->id,
                'user_id'        => $userId,
                'payment_id'     => $paymentId,
                // store applied amount in `amount` for reporting
                'amount'         => $applied,
                'amount_given'   => $amountGiven,
                'change'         => $change,
                'paid_at'        => now(),
            ]);

            // increment paid_amount on transactions table by applied amount
            if ($applied > 0) {
                $this->increment('paid_amount', $applied);
            }

            // refresh model to get updated paid_amount
            $this->refresh();

            // update payment_status based on new paid_amount
            if ($this->paid_amount >= $this->grand_total && $this->grand_total > 0) {
                $this->update(['payment_status' => 'paid']);
            } elseif ($this->paid_amount > 0 && $this->paid_amount < $this->grand_total) {
                $this->update(['payment_status' => 'partial']);
            } else {
                $this->update(['payment_status' => 'unpaid']);
            }

            return $attempt;
        });
    }
}
