<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class GoldPledge extends Model
{
    protected $fillable = [
        'user_id',
        'gold_amount',
        'price_per_gram',
        'appraisal_value',
        'loan_amount',
        'admin_fee',
        'storage_fee_rate',
        'total_amount',
        'paid_amount',
        'duration_months',
        'extension_count',
        'start_date',
        'end_date',
        'status',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'gold_amount' => 'decimal:4',
            'price_per_gram' => 'decimal:2',
            'appraisal_value' => 'decimal:2',
            'loan_amount' => 'decimal:2',
            'admin_fee' => 'decimal:2',
            'storage_fee_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user who owns this pledge
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved this pledge
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all payments for this pledge
     */
    public function payments(): HasMany
    {
        return $this->hasMany(PledgePayment::class, 'pledge_id');
    }

    /**
     * Get all extensions for this pledge
     */
    public function extensions(): HasMany
    {
        return $this->hasMany(PledgeExtension::class, 'pledge_id');
    }

    /**
     * Scope: Get active pledges
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get pending pledges
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get overdue pledges
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Calculate progress percentage
     */
    public function calculateProgress(): float
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return ($this->paid_amount / $this->total_amount) * 100;
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    /**
     * Check if pledge is paid off
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->paid_amount >= $this->total_amount;
    }

    /**
     * Check if pledge is overdue
     */
    public function isOverdue(): bool
    {
        if (!$this->end_date) {
            return false;
        }
        
        $endDate = $this->end_date instanceof Carbon ? $this->end_date : Carbon::parse($this->end_date);
        
        return $this->status === 'overdue' || 
               ($this->status === 'active' && $endDate->toDateString() < Carbon::now()->toDateString() && !$this->isPaid());
    }

    /**
     * Calculate monthly installment
     */
    public function getMonthlyInstallment(): float
    {
        if ($this->duration_months == 0) {
            return 0;
        }
        return $this->total_amount / $this->duration_months;
    }

    /**
     * Calculate storage fee for a period
     */
    public function calculateStorageFee($months = 1): float
    {
        return ($this->loan_amount * $this->storage_fee_rate / 100) * $months;
    }
}
