<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address'
    ];

    /**
     * Get all batches from this supplier.
     */
    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }
}
