<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id', 'amount', 'payment_date',
        'method', 'reference_no', 'notes', 'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function methodLabel(string $method): string
    {
        return match($method) {
            'cash'          => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque'        => 'Cheque',
            'online'        => 'Online/Mobile',
            default         => 'Other',
        };
    }
}
