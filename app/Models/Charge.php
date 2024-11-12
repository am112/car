<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Charge extends Model
{
    use HasFactory;

    public const PREFIX = 'LATE';

    public const TYPE_LATE = 'late';
    public const TYPE_PENALTY = 'penalty';

    protected $guarded = [];

    // ----------------- helpers function --------------
    public static function isLateChargeable(int $unresolvedInvoiceAmount, Carbon $invoiceDate, Carbon $lateChargeDate, int $unresolvedInvoiceCount = 0): bool
    {

        if ($invoiceDate->gt($lateChargeDate)) {
            return false;
        }

        if ($unresolvedInvoiceCount >= 2) {
            return true;
        }

        if ($unresolvedInvoiceAmount > 0) {
            if ($invoiceDate->diffInDays($lateChargeDate) > 8) {
                return true;
            }

            return false;
        }

        if ($invoiceDate->diffInDays($lateChargeDate) > 8) {
            return true;
        }

        return false;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function scopeUnresolved(Builder $query): void
    {
        $query->where('unresolved', true);
    }

    public function scopeResolved(Builder $query): void
    {
        $query->where('unresolved', false);
    }
}
