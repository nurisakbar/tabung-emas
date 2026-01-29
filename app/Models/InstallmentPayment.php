<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class InstallmentPayment extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'schedule_id',
        'payment_amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'allocated_gold',
        'status',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'payment_amount' => 'decimal:2',
            'allocated_gold' => 'decimal:4',
            'payment_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the installment plan for this payment
     */
    public function installmentPlan(): BelongsTo
    {
        return $this->belongsTo(GoldInstallmentPlan::class, 'installment_plan_id');
    }

    /**
     * Get the schedule for this payment (if applicable)
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(InstallmentSchedule::class, 'schedule_id');
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
     * Scope: Get rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
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
