<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_type',
        'gold_amount',
        'price_per_gram',
        'total_price',
        'transaction_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gold_amount' => 'decimal:4',
            'price_per_gram' => 'decimal:2',
            'total_price' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    /**
     * Get the user who made this transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if transaction is buy type
     */
    public function isBuy(): bool
    {
        return $this->transaction_type === 'buy';
    }

    /**
     * Check if transaction is sell type
     */
    public function isSell(): bool
    {
        return $this->transaction_type === 'sell';
    }
}
