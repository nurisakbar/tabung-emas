<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoldPrice extends Model
{
    protected $fillable = [
        'date',
        'buy_price',
        'sell_price',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'buy_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
        ];
    }

    /**
     * Get the admin who created this price
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
