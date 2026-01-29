<?php

namespace Database\Seeders;

use App\Models\GoldInstallmentPlan;
use App\Models\InstallmentSchedule;
use App\Models\InstallmentPayment;
use App\Models\GoldPrice;
use App\Models\GoldSaving;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstallmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nasabahs = User::where('role', 'nasabah')->get();
        $latestPrice = GoldPrice::latest('date')->first();

        if ($nasabahs->isEmpty() || !$latestPrice) {
            return;
        }

        // Create sample installment plans for some nasabahs
        foreach ($nasabahs->take(3) as $index => $nasabah) {
            $scenarios = [
                [
                    'gold_amount' => 2.5,
                    'tenor' => 6,
                    'frequency' => 'monthly',
                    'down_payment' => 0,
                    'status' => 'active',
                    'paid_installments' => 2, // Already paid 2 installments
                ],
                [
                    'gold_amount' => 5.0,
                    'tenor' => 12,
                    'frequency' => 'monthly',
                    'down_payment' => 500000,
                    'status' => 'active',
                    'paid_installments' => 0, // Just created, no payment yet
                ],
                [
                    'gold_amount' => 1.0,
                    'tenor' => 3,
                    'frequency' => 'monthly',
                    'down_payment' => 0,
                    'status' => 'completed',
                    'paid_installments' => 3, // Fully paid
                ],
            ];

            if (isset($scenarios[$index])) {
                $scenario = $scenarios[$index];
                
                // Calculate values
                $totalPrice = $scenario['gold_amount'] * $latestPrice->buy_price;
                $priceAfterDP = $totalPrice - $scenario['down_payment'];
                $totalPeriods = $scenario['tenor'];
                $installmentAmount = $totalPeriods > 0 ? $priceAfterDP / $totalPeriods : 0;

                // Create installment plan
                $plan = GoldInstallmentPlan::create([
                    'user_id' => $nasabah->id,
                    'gold_amount' => $scenario['gold_amount'],
                    'price_per_gram' => $latestPrice->buy_price,
                    'total_price' => round($totalPrice, 2),
                    'down_payment' => $scenario['down_payment'],
                    'installment_amount' => round($installmentAmount, 2),
                    'total_installments' => $totalPeriods,
                    'paid_installments' => $scenario['paid_installments'],
                    'frequency' => $scenario['frequency'],
                    'status' => $scenario['status'],
                    'start_date' => Carbon::now()->subMonths($scenario['paid_installments']),
                    'end_date' => Carbon::now()->subMonths($scenario['paid_installments'])->addMonths($scenario['tenor']),
                    'notes' => 'Paket cicil emas - Seeder',
                ]);

                // Generate schedules
                $currentDate = $plan->start_date->copy();
                $schedules = [];

                for ($i = 1; $i <= $plan->total_installments; $i++) {
                    if ($plan->frequency === 'weekly') {
                        $currentDate->addWeek();
                    } else {
                        $currentDate->addMonth();
                    }

                    $isPaid = $i <= $scenario['paid_installments'];
                    $isOverdue = false;
                    
                    if ($scenario['status'] === 'active' && !$isPaid && $currentDate < Carbon::now()) {
                        $isOverdue = true;
                    }

                    $schedules[] = [
                        'installment_plan_id' => $plan->id,
                        'installment_number' => $i,
                        'due_date' => $currentDate->toDateString(),
                        'amount' => $plan->installment_amount,
                        'paid_amount' => $isPaid ? $plan->installment_amount : 0,
                        'status' => $isPaid ? 'paid' : ($isOverdue ? 'overdue' : 'pending'),
                        'paid_at' => $isPaid ? Carbon::now()->subDays(rand(1, 30)) : null,
                        'late_fee' => $isOverdue ? round($plan->installment_amount * 0.02, 2) : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                InstallmentSchedule::insert($schedules);

                // Create payment records for paid installments
                if ($scenario['paid_installments'] > 0) {
                    $paidSchedules = InstallmentSchedule::where('installment_plan_id', $plan->id)
                        ->where('status', 'paid')
                        ->get();

                    foreach ($paidSchedules as $schedule) {
                        $allocatedGold = ($plan->installment_amount / $plan->total_price) * $plan->gold_amount;

                        $payment = InstallmentPayment::create([
                            'installment_plan_id' => $plan->id,
                            'schedule_id' => $schedule->id,
                            'payment_amount' => $plan->installment_amount,
                            'payment_date' => $schedule->paid_at->toDateString(),
                            'payment_method' => 'transfer',
                            'reference_number' => 'TRF' . str_pad($plan->id . $schedule->id, 10, '0', STR_PAD_LEFT),
                            'allocated_gold' => round($allocatedGold, 4),
                            'status' => 'verified',
                            'verified_by' => User::where('role', 'admin')->first()->id,
                            'verified_at' => $schedule->paid_at,
                            'notes' => 'Pembayaran cicilan - Seeder',
                        ]);
                    }

                    // Update gold saving for allocated gold
                    $totalAllocatedGold = InstallmentPayment::where('installment_plan_id', $plan->id)
                        ->where('status', 'verified')
                        ->sum('allocated_gold');

                    $goldSaving = \App\Models\GoldSaving::firstOrCreate(
                        ['user_id' => $nasabah->id],
                        ['total_gold' => 0]
                    );

                    $goldSaving->total_gold += $totalAllocatedGold;
                    $goldSaving->last_transaction_date = now();
                    $goldSaving->save();
                }

                // Create one pending payment for active plans
                if ($scenario['status'] === 'active' && $scenario['paid_installments'] < $scenario['tenor']) {
                    $nextSchedule = InstallmentSchedule::where('installment_plan_id', $plan->id)
                        ->where('status', 'pending')
                        ->orderBy('due_date')
                        ->first();

                    if ($nextSchedule) {
                        $allocatedGold = ($plan->installment_amount / $plan->total_price) * $plan->gold_amount;

                        InstallmentPayment::create([
                            'installment_plan_id' => $plan->id,
                            'schedule_id' => $nextSchedule->id,
                            'payment_amount' => $plan->installment_amount,
                            'payment_date' => Carbon::now()->subDays(2)->toDateString(),
                            'payment_method' => 'transfer',
                            'reference_number' => 'TRF' . str_pad($plan->id . $nextSchedule->id, 10, '0', STR_PAD_LEFT),
                            'allocated_gold' => round($allocatedGold, 4),
                            'status' => 'pending',
                            'notes' => 'Pembayaran menunggu verifikasi - Seeder',
                        ]);
                    }
                }
            }
        }
    }
}
