<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class InstallmentSchedule extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'installment_number',
        'due_date',
        'amount',
        'paid_amount',
        'status',
        'paid_at',
        'late_fee',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Get the installment plan that owns this schedule
     */
    public function installmentPlan(): BelongsTo
    {
        return $this->belongsTo(GoldInstallmentPlan::class, 'installment_plan_id');
    }

    /**
     * Get all payments for this schedule
     */
    public function payments(): HasMany
    {
        return $this->hasMany(InstallmentPayment::class, 'schedule_id');
    }

    /**
     * Scope: Get pending schedules
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get paid schedules
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Get overdue schedules
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope: Get schedules due soon (within X days)
     */
    public function scopeDueSoon($query, $days = 7)
    {
        $date = Carbon::now()->addDays($days);
        return $query->where('status', 'pending')
            ->where('due_date', '<=', $date)
            ->where('due_date', '>=', Carbon::now());
    }

    /**
     * Check if schedule is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' || 
               ($this->status === 'pending' && $this->due_date < Carbon::now()->toDateString());
    }

    /**
     * Calculate late fee (if overdue)
     */
    public function calculateLateFee($feeRate = 0.02): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $daysOverdue = Carbon::now()->diffInDays($this->due_date);
        return $this->amount * $feeRate * $daysOverdue;
    }

    /**
     * Mark schedule as paid
     */
    public function markAsPaid($paidAmount = null): void
    {
        $this->status = 'paid';
        $this->paid_amount = $paidAmount ?? $this->amount;
        $this->paid_at = Carbon::now();
        $this->save();
    }
}
