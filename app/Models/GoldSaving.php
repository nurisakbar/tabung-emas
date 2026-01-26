<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoldSaving extends Model
{
    protected $fillable = [
        'user_id',
        'total_gold',
        'last_transaction_date',
    ];

    protected function casts(): array
    {
        return [
            'total_gold' => 'decimal:4',
            'last_transaction_date' => 'date',
        ];
    }

    /**
     * Get the user who owns this gold saving
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
