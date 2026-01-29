<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PledgePayment extends Model
{
    protected $fillable = [
        'pledge_id',
        'payment_type',
        'payment_amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'status',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_amount' => 'decimal:2',
            'payment_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the pledge for this payment
     */
    public function pledge(): BelongsTo
    {
        return $this->belongsTo(GoldPledge::class, 'pledge_id');
    }

    /**
     * Get the admin who verified this payment
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope: Get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Verify payment
     */
    public function verify($verifiedBy): void
    {
        $this->status = 'verified';
        $this->verified_by = $verifiedBy;
        $this->verified_at = Carbon::now();
        $this->save();
    }

    /**
     * Reject payment
     */
    public function reject($verifiedBy, $notes = null): void
    {
        $this->status = 'rejected';
        $this->verified_by = $verifiedBy;
        $this->verified_at = Carbon::now();
        if ($notes) {
            $this->notes = $notes;
        }
        $this->save();
    }
}
