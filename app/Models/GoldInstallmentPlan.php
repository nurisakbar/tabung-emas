<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoldInstallmentPlan extends Model
{
    protected $fillable = [
        'user_id',
        'gold_amount',
        'price_per_gram',
        'total_price',
        'down_payment',
        'installment_amount',
        'total_installments',
        'paid_installments',
        'frequency',
        'status',
        'start_date',
        'end_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gold_amount' => 'decimal:4',
            'price_per_gram' => 'decimal:2',
            'total_price' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'installment_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * Get the user who owns this installment plan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all schedules for this installment plan
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(InstallmentSchedule::class, 'installment_plan_id');
    }

    /**
     * Get all payments for this installment plan
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InstallmentPayment::class, 'installment_plan_id');
    }

    /**
     * Scope: Get active plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Get completed plans
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Get overdue plans
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
        if ($this->total_installments == 0) {
            return 0;
        }
        return ($this->paid_installments / $this->total_installments) * 100;
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount(): float
    {
        $totalPaid = $this->payments()
            ->where('status', 'verified')
            ->sum('payment_amount');
        
        return max(0, $this->total_price - $totalPaid);
    }

    /**
     * Check if plan is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->paid_installments >= $this->total_installments;
    }

    /**
     * Check if plan can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'active']) && $this->paid_installments == 0;
    }
}
