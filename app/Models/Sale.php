<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'discount_type',
        'discount_amount',
        'payment_method',
        'cash_received',
        'change_amount',
        'status',
        'date_sold'
    ];

    protected $casts = [
        'date_sold' => 'date',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Get the user (cashier) who processed this sale.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in this sale.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the returns for this sale.
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnAndRefund::class);
    }

    /**
     * Calculate and update total amount.
     */
    public function calculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->save();
    }
}
