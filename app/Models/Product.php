<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image_path',
        'brand',
        'size',
        'date_added'
    ];

    protected $casts = [
        'date_added' => 'date',
    ];

    /**
     * Get the category of this product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    /**
     * Get all inventory records for this product.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get all sales items for this product.
     */
    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get all batches for this product.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    /**
     * Get the latest batch for this product.
     */
    public function latestBatch()
    {
        return $this->hasOne(ProductBatch::class)->latestOfMany();
    }

    /**
     * Get the first available batch (FIFO) for this product.
     */
    public function firstAvailableBatch()
    {
        return $this->hasOne(ProductBatch::class)
            ->ofMany(['date_received' => 'min', 'id' => 'min'], function ($query) {
                $query->where('quantity', '>', 0);
            });
    }

    /**
     * Get all returns for this product.
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnAndRefund::class);
    }
}
