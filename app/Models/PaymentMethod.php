<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_charge_id',
        'name',
        'requires_reference',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_reference' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function paymentCharge(): BelongsTo
    {
        return $this->belongsTo(PaymentCharge::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
