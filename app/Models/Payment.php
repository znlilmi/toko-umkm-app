<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_status',
        'amount_paid',
        'proof_of_payment',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_id' => 'integer',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the order that the payment is for.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
