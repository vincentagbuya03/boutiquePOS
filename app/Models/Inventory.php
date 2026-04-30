<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $fillable = [
        'product_id',
        'quantity',
        'reorder_level',
        'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'date'
    ];

    /**
     * Get the product associated with this inventory record.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Check if this product is below reorder level.
     */
    public function isBelowReorderLevel(): bool
    {
        return $this->quantity <= $this->reorder_level;
    }

    /**
     * Get available quantity.
     */
    public function getAvailableQuantity(): int
    {
        return max(0, $this->quantity);
    }
}
