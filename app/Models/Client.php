<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'business_name', 'ntn', 'strn', 'province', 'default_gst_rate',
        'address', 'city', 'phone', 'email', 'logo',
        'invoice_prefix', 'invoice_counter', 'profile_complete', 'is_active',
    ];

    protected $casts = [
        'profile_complete' => 'boolean',
        'is_active' => 'boolean',
        'default_gst_rate' => 'decimal:2',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $number = str_pad($this->invoice_counter, 5, '0', STR_PAD_LEFT);
        return "{$this->invoice_prefix}-{$year}-{$number}";
    }

    public function incrementInvoiceCounter(): void
    {
        $this->increment('invoice_counter');
    }

    public static function provinces(): array
    {
        return [
            'FBR'  => 'Federal (FBR)',
            'SRB'  => 'Sindh (SRB)',
            'PRA'  => 'Punjab (PRA)',
            'KPRA' => 'KPK (KPRA)',
            'BRA'  => 'Balochistan (BRA)',
        ];
    }
}
