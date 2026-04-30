<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnAndRefund extends Model
{
    protected $table = 'returns_and_refunds';

    protected $fillable = [
        'sale_id',
        'online_order_id',
        'product_id',
        'quantity_returned',
        'reason',
        'description',
        'status',
        'action',
        'refund_amount',
        'return_date',
        'processed_date',
        'processed_by'
    ];

    protected $casts = [
        'return_date' => 'date',
        'processed_date' => 'date',
        'refund_amount' => 'decimal:2'
    ];

    /**
     * Get the sale associated with this return (if any).
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the online order associated with this return (if any).
     */
    public function onlineOrder(): BelongsTo
    {
        return $this->belongsTo(OnlineOrder::class);
    }

    /**
     * Get the product being returned.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * Get the staff member who processed this return.
     */
    public function processedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Check if return is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Approve the return and process refund/replacement.
     */
    public function approve(string $action, ?float $refundAmount = null, int $processedBy = null): void
    {
        $this->status = 'approved';
        $this->action = $action;
        $this->refund_amount = $refundAmount;
        $this->processed_by = $processedBy;
        $this->processed_date = now()->toDateString();
        $this->save();
    }
}
