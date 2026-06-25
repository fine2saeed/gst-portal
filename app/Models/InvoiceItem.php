<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'product_id', 'description', 'hs_code',
        'qty', 'unit', 'unit_price', 'gst_rate', 'gst_amount', 'total',
    ];

    protected $casts = [
        'qty'        => 'decimal:3',
        'unit_price' => 'decimal:2',
        'gst_rate'   => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
