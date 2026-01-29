<?php

namespace App\Services;

use App\Models\GoldPledge;
use App\Models\PledgePayment;
use App\Models\PledgeExtension;
use App\Models\GoldSaving;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PledgeService
{
    protected PledgeCalculatorService $calculator;

    public function __construct(PledgeCalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Create a new pledge request
     *
     * @param User $user
     * @param array $data
     * @return GoldPledge
     */
    public function createPledge(User $user, array $data): GoldPledge
    {
        // First calculate appraisal to get max loan
        $appraisal = $this->calculator->calculateAppraisal(
            $data['gold_amount'],
            $data['sell_price'],
            $data['appraisal_rate'] ?? 0.85
        );

        // Use user's loan amount but ensure it doesn't exceed max
        $loanAmount = min($data['loan_amount'], $appraisal['max_loan']);

        // Then calculate total with the loan amount
        $calculation = $this->calculator->calculateTotal(
            $loanAmount,
            $data['duration_months'],
            $data['admin_fee'] ?? 50000,
            $data['storage_fee_rate'] ?? 1.5
        );

        // Merge appraisal and calculation
        $calculation = array_merge($appraisal, $calculation);
        $calculation['loan_amount'] = $loanAmount;

        // Check if user has enough gold
        $goldSaving = $user->goldSaving;
        if (!$goldSaving || $goldSaving->total_gold < $data['gold_amount']) {
            throw new \Exception('Jumlah emas tidak mencukupi. Anda memiliki ' . ($goldSaving ? number_format($goldSaving->total_gold, 4) : 0) . ' gram.');
        }

        DB::beginTransaction();
        try {
            $startDate = Carbon::now();
            $durationMonths = (int) $data['duration_months'];
            $endDate = $startDate->copy()->addMonths($durationMonths);

            $pledge = GoldPledge::create([
                'user_id' => $user->id,
                'gold_amount' => $data['gold_amount'],
                'price_per_gram' => $data['sell_price'],
                'appraisal_value' => $calculation['appraisal_value'],
                'loan_amount' => $calculation['loan_amount'],
                'admin_fee' => $calculation['admin_fee'],
                'storage_fee_rate' => $calculation['storage_fee_rate'],
                'total_amount' => $calculation['total_amount'],
                'duration_months' => $durationMonths,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            // Lock gold in GoldSaving (we'll add a locked_gold field or use notes)
            // For now, we'll just create the pledge and admin will handle the locking

            DB::commit();
            return $pledge;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve pledge request
     *
     * @param GoldPledge $pledge
     * @param int $approvedBy
     * @return void
     */
    public function approvePledge(GoldPledge $pledge, int $approvedBy): void
    {
        DB::beginTransaction();
        try {
            $pledge->status = 'active';
            $pledge->approved_by = $approvedBy;
            $pledge->approved_at = Carbon::now();
            $pledge->save();

            // Lock gold in user's savings
            $goldSaving = GoldSaving::firstOrCreate(
                ['user_id' => $pledge->user_id],
                ['total_gold' => 0]
            );

            // For now, we'll just note that gold is pledged
            // In production, you might want to add a locked_gold field
            // or create a separate table for locked gold

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process payment
     *
     * @param GoldPledge $pledge
     * @param array $paymentData
     * @return PledgePayment
     */
    public function processPayment(GoldPledge $pledge, array $paymentData): PledgePayment
    {
        DB::beginTransaction();
        try {
            $payment = PledgePayment::create([
                'pledge_id' => $pledge->id,
                'payment_type' => $paymentData['payment_type'] ?? 'installment',
                'payment_amount' => $paymentData['payment_amount'],
                'payment_date' => $paymentData['payment_date'] ?? Carbon::now()->toDateString(),
                'payment_method' => $paymentData['payment_method'] ?? 'transfer',
                'reference_number' => $paymentData['reference_number'] ?? null,
                'status' => 'pending',
                'notes' => $paymentData['notes'] ?? null,
            ]);

            // If auto-verify is enabled
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
     * Verify payment and update pledge
     *
     * @param PledgePayment $payment
     * @param int|null $verifiedBy
     * @return void
     */
    public function verifyPayment(PledgePayment $payment, ?int $verifiedBy = null): void
    {
        DB::beginTransaction();
        try {
            $payment->verify($verifiedBy ?? auth()->id());

            $pledge = $payment->pledge;
            $pledge->paid_amount += $payment->payment_amount;

            // Check if fully paid
            if ($pledge->paid_amount >= $pledge->total_amount) {
                $pledge->status = 'paid';
            }

            $pledge->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Redeem gold (after full payment)
     *
     * @param GoldPledge $pledge
     * @return void
     */
    public function redeemGold(GoldPledge $pledge): void
    {
        if (!$pledge->isPaid()) {
            throw new \Exception('Pledge belum lunas. Sisa yang harus dibayar: Rp ' . number_format($pledge->getRemainingAmount(), 0, ',', '.'));
        }

        DB::beginTransaction();
        try {
            $pledge->status = 'paid';
            $pledge->save();

            // Unlock gold - gold is already in GoldSaving, just unlock it
            // In production, you would update locked_gold field

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Extend pledge duration
     *
     * @param GoldPledge $pledge
     * @param array $extensionData
     * @return PledgeExtension
     */
    public function extendPledge(GoldPledge $pledge, array $extensionData): PledgeExtension
    {
        DB::beginTransaction();
        try {
            $extension = PledgeExtension::create([
                'pledge_id' => $pledge->id,
                'extension_duration' => $extensionData['extension_duration'],
                'extension_fee' => $extensionData['extension_fee'],
                'new_end_date' => $extensionData['new_end_date'],
                'status' => 'pending',
                'notes' => $extensionData['notes'] ?? null,
            ]);

            DB::commit();
            return $extension;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approve extension
     *
     * @param PledgeExtension $extension
     * @param int $approvedBy
     * @return void
     */
    public function approveExtension(PledgeExtension $extension, int $approvedBy): void
    {
        DB::beginTransaction();
        try {
            $extension->approve($approvedBy);

            $pledge = $extension->pledge;
            $pledge->end_date = $extension->new_end_date;
            $pledge->extension_count += 1;
            $pledge->total_amount += $extension->extension_fee;
            $pledge->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check and update overdue pledges
     *
     * @return int Number of pledges updated
     */
    public function checkOverduePledges(): int
    {
        $overduePledges = GoldPledge::where('status', 'active')
            ->where('end_date', '<', Carbon::now()->toDateString())
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->get();

        foreach ($overduePledges as $pledge) {
            $pledge->status = 'overdue';
            $pledge->save();
        }

        return $overduePledges->count();
    }
}
