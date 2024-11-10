<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'contract_at' => 'datetime:d/m/Y h:i A',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function credits(): HasMany
    {
        return $this->hasMany(Credit::class);
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function scopeRunnableOn(Builder $query, array $days): void
    {
        $query->whereRaw('DAY(contract_at) in (' . implode(',', $days) . ')');
    }
}
