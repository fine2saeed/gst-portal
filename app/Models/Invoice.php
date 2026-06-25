<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'client_id', 'customer_id', 'invoice_no', 'invoice_date', 'due_date',
        'status', 'payment_status', 'amount_paid',
        'subtotal', 'gst_amount', 'discount', 'total',
        'notes', 'cancellation_reason',
        'fbr_status', 'fbr_irn', 'fbr_qr', 'fbr_submitted_at', 'created_by',
    ];

    protected $casts = [
        'invoice_date'     => 'date',
        'due_date'         => 'date',
        'fbr_submitted_at' => 'datetime',
        'subtotal'         => 'decimal:2',
        'gst_amount'       => 'decimal:2',
        'discount'         => 'decimal:2',
        'total'            => 'decimal:2',
        'amount_paid'      => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class)->latest();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isFinal(): bool
    {
        return $this->status === 'final';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function balance(): float
    {
        return max(0, (float) $this->total - (float) $this->amount_paid);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPartiallyPaid(): bool
    {
        return $this->payment_status === 'partial';
    }

    public function recalculatePaymentStatus(): void
    {
        $paid = (float) $this->amount_paid;
        $total = (float) $this->total;

        if ($paid <= 0) {
            $status = 'unpaid';
        } elseif ($paid >= $total) {
            $status = 'paid';
        } else {
            $status = 'partial';
        }

        $this->update(['payment_status' => $status]);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft'     => '<span class="badge badge-draft">Draft</span>',
            'final'     => '<span class="badge badge-final">Final</span>',
            'cancelled' => '<span class="badge badge-cancelled">Cancelled</span>',
            default     => $this->status,
        };
    }
}
