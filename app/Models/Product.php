<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'client_id', 'name', 'description', 'hs_code',
        'price', 'gst_rate', 'tax_type', 'unit', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'gst_rate' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getTaxTypeLabelAttribute(): string
    {
        return match($this->tax_type) {
            'zero_rated' => 'Zero Rated',
            'exempt'     => 'Exempt',
            default      => 'Standard (' . $this->gst_rate . '%)',
        };
    }
}
