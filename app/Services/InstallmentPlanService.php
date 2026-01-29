<?php

namespace App\Services;

use App\Models\GoldInstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\InstallmentPayment;
use App\Models\GoldSaving;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstallmentPlanService
{
    protected InstallmentCalculatorService $calculator;

    public function __construct(InstallmentCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Create a new installment plan
     *
     * @param User $user
     * @param array $data
     * @return GoldInstallmentPlan
     */
    public function createPlan(User $user, array $data): GoldInstallmentPlan
    {
        $calculation = $this->calculator->calculate(
            $data['gold_amount'],
            $data['price_per_gram'],
            $data['tenor'],
            $data['frequency'] ?? 'monthly',
            $data['admin_fee'] ?? 0,
            $data['down_payment'] ?? 0
        );

        $startDate = isset($data['start_date']) 
            ? Carbon::parse($data['start_date']) 
            : Carbon::now();

        DB::beginTransaction();
        try {
            // Create installment plan
            $plan = GoldInstallmentPlan::create([
                'user_id' => $user->id,
                'gold_amount' => $calculation['gold_amount'],
                'price_per_gram' => $calculation['price_per_gram'],
                'total_price' => $calculation['total_price'],
                'down_payment' => $calculation['down_payment'],
                'installment_amount' => $calculation['installment_amount'],
                'total_installments' => $calculation['total_installments'],
                'frequency' => $calculation['frequency'],
                'status' => $data['down_payment'] > 0 ? 'pending' : 'active',
                'start_date' => $startDate,
                'notes' => $data['notes'] ?? null,
            ]);

            // Generate schedules
            $this->generateSchedules($plan, $startDate);

            // Process down payment if exists
            if ($calculation['down_payment'] > 0) {
                // Down payment will be processed separately
            }

            DB::commit();
            return $plan;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Generate payment schedules for a plan
     *
     * @param GoldInstallmentPlan $plan
     * @param Carbon $startDate
     * @return void
     */
    public function generateSchedules(GoldInstallmentPlan $plan, Carbon $startDate): void
    {
        $currentDate = $startDate->copy();
        $schedules = [];

        for ($i = 1; $i <= $plan->total_installments; $i++) {
            if ($plan->frequency === 'weekly') {
                $currentDate->addWeek();
            } else {
                $currentDate->addMonth();
            }

            $schedules[] = [
                'installment_plan_id' => $plan->id,
                'installment_number' => $i,
                'due_date' => $currentDate->toDateString(),
                'amount' => $plan->installment_amount,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        InstallmentSchedule::insert($schedules);

        // Update end date
        $plan->end_date = $currentDate->toDateString();
        $plan->save();
    }

    /**
     * Process payment for an installment plan
     *
     * @param GoldInstallmentPlan $plan
     * @param array $paymentData
     * @return InstallmentPayment
     */
    public function processPayment(GoldInstallmentPlan $plan, array $paymentData): InstallmentPayment
    {
        DB::beginTransaction();
        try {
            // Find the schedule to pay (if specified)
            $schedule = null;
            if (isset($paymentData['schedule_id'])) {
                $schedule = InstallmentSchedule::find($paymentData['schedule_id']);
            } else {
                // Find next pending schedule
                $schedule = $plan->schedules()
                    ->where('status', 'pending')
                    ->orderBy('due_date')
                    ->first();
            }

            $paymentAmount = $paymentData['payment_amount'];
            $allocatedGold = $this->calculateAllocatedGold($plan, $paymentAmount);

            // Create payment record
            $payment = InstallmentPayment::create([
                'installment_plan_id' => $plan->id,
                'schedule_id' => $schedule?->id,
                'payment_amount' => $paymentAmount,
                'payment_date' => $paymentData['payment_date'] ?? Carbon::now()->toDateString(),
                'payment_method' => $paymentData['payment_method'] ?? 'transfer',
                'reference_number' => $paymentData['reference_number'] ?? null,
                'allocated_gold' => $allocatedGold,
                'status' => 'pending', // Will be verified by admin
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // If auto-verify is enabled (or if admin verified immediately)
            if ($paymentData['auto_verify'] ?? false) {
                $this->verifyPayment($payment, $paymentData['verified_by'] ?? null);
            }

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify payment and allocate gold
     *
     * @param InstallmentPayment $payment
     * @param int|null $verifiedBy
     * @return void
     */
    public function verifyPayment(InstallmentPayment $payment, ?int $verifiedBy = null): void
    {
        DB::beginTransaction();
        try {
            $payment->verify($verifiedBy ?? auth()->id());

            $plan = $payment->installmentPlan;
            $schedule = $payment->schedule;

            // Update schedule if exists
            if ($schedule) {
                $schedule->paid_amount += $payment->payment_amount;
                if ($schedule->paid_amount >= $schedule->amount) {
                    $schedule->markAsPaid($schedule->paid_amount);
                } else {
                    $schedule->save();
                }
            }

            // Allocate gold to user's savings
            $this->allocateGold($plan->user, $payment->allocated_gold);

            // Update plan progress
            $this->updatePlanProgress($plan);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Calculate allocated gold based on payment amount
     *
     * @param GoldInstallmentPlan $plan
     * @param float $paymentAmount
     * @return float
     */
    private function calculateAllocatedGold(GoldInstallmentPlan $plan, float $paymentAmount): float
    {
        if ($plan->total_price == 0) {
            return 0;
        }
        return ($paymentAmount / $plan->total_price) * $plan->gold_amount;
    }

    /**
     * Allocate gold to user's savings
     *
     * @param User $user
     * @param float $goldAmount
     * @return void
     */
    private function allocateGold(User $user, float $goldAmount): void
    {
        $goldSaving = GoldSaving::firstOrCreate(
            ['user_id' => $user->id],
            ['total_gold' => 0]
        );

        $goldSaving->total_gold += $goldAmount;
        $goldSaving->last_transaction_date = now();
        $goldSaving->save();
    }

    /**
     * Update plan progress
     *
     * @param GoldInstallmentPlan $plan
     * @return void
     */
    private function updatePlanProgress(GoldInstallmentPlan $plan): void
    {
        $paidSchedules = $plan->schedules()->where('status', 'paid')->count();
        $plan->paid_installments = $paidSchedules;

        if ($plan->paid_installments >= $plan->total_installments) {
            $plan->status = 'completed';
        } elseif ($plan->status === 'pending') {
            $plan->status = 'active';
        }

        $plan->save();
    }

    /**
     * Process early payment (full payment)
     *
     * @param GoldInstallmentPlan $plan
     * @param array $paymentData
     * @return InstallmentPayment
     */
    public function processEarlyPayment(GoldInstallmentPlan $plan, array $paymentData): InstallmentPayment
    {
        $remainingAmount = $plan->getRemainingAmount();
        
        if ($remainingAmount <= 0) {
            throw new \Exception('Plan is already completed');
        }

        $paymentData['payment_amount'] = $remainingAmount;
        $payment = $this->processPayment($plan, $paymentData);

        // Mark all remaining schedules as paid
        $plan->schedules()
            ->where('status', 'pending')
            ->get()
            ->each(function ($schedule) use ($plan) {
                $schedule->markAsPaid($schedule->amount);
            });

        // Verify payment immediately for early payment
        $this->verifyPayment($payment, $paymentData['verified_by'] ?? null);

        return $payment;
    }

    /**
     * Check and update overdue plans
     *
     * @return int Number of plans updated
     */
    public function checkOverduePlans(): int
    {
        $overdueSchedules = InstallmentSchedule::where('status', 'pending')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->with('installmentPlan')
            ->get();

        $updatedPlans = [];

        foreach ($overdueSchedules as $schedule) {
            $schedule->status = 'overdue';
            $schedule->late_fee = $schedule->calculateLateFee();
            $schedule->save();

            $planId = $schedule->installment_plan_id;
            if (!in_array($planId, $updatedPlans)) {
                $plan = $schedule->installmentPlan;
                if ($plan->status !== 'overdue') {
                    $plan->status = 'overdue';
                    $plan->save();
                }
                $updatedPlans[] = $planId;
            }
        }

        return count($updatedPlans);
    }

    /**
     * Cancel installment plan
     *
     * @param GoldInstallmentPlan $plan
     * @param string|null $reason
     * @return void
     */
    public function cancelPlan(GoldInstallmentPlan $plan, ?string $reason = null): void
    {
        if (!$plan->canBeCancelled()) {
            throw new \Exception('Plan cannot be cancelled');
        }

        $plan->status = 'cancelled';
        if ($reason) {
            $plan->notes = ($plan->notes ? $plan->notes . "\n\n" : '') . "Cancelled: " . $reason;
        }
        $plan->save();
    }
}
