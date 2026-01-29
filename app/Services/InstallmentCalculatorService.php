<?php

namespace App\Services;

use Carbon\Carbon;

class InstallmentCalculatorService
{
    /**
     * Calculate installment details
     *
     * @param float $goldAmount
     * @param float $pricePerGram
     * @param int $tenor
     * @param string $frequency
     * @param float $adminFee
     * @param float $downPayment
     * @return array
     */
    public function calculate(
        float $goldAmount,
        float $pricePerGram,
        int $tenor,
        string $frequency = 'monthly',
        float $adminFee = 0,
        float $downPayment = 0
    ): array {
        $totalPrice = $goldAmount * $pricePerGram;
        $priceAfterDP = $totalPrice - $downPayment;
        $totalWithFee = $priceAfterDP + $adminFee;
        
        // Calculate installment amount based on frequency
        if ($frequency === 'weekly') {
            $totalPeriods = $tenor * 4; // Approximate weeks per month
        } else {
            $totalPeriods = $tenor;
        }
        
        $installmentAmount = $totalPeriods > 0 ? $totalWithFee / $totalPeriods : 0;
        
        return [
            'gold_amount' => $goldAmount,
            'price_per_gram' => $pricePerGram,
            'total_price' => round($totalPrice, 2),
            'down_payment' => round($downPayment, 2),
            'admin_fee' => round($adminFee, 2),
            'price_after_dp' => round($priceAfterDP, 2),
            'total_with_fee' => round($totalWithFee, 2),
            'installment_amount' => round($installmentAmount, 2),
            'total_installments' => $totalPeriods,
            'frequency' => $frequency,
        ];
    }

    /**
     * Simulate installment plan with schedule preview
     *
     * @param array $params
     * @return array
     */
    public function simulate(array $params): array
    {
        $calculation = $this->calculate(
            $params['gold_amount'],
            $params['price_per_gram'],
            $params['tenor'],
            $params['frequency'] ?? 'monthly',
            $params['admin_fee'] ?? 0,
            $params['down_payment'] ?? 0
        );

        $startDate = $params['start_date'] ?? Carbon::now();
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }

        $schedules = $this->generateSchedulePreview(
            $startDate,
            $calculation['total_installments'],
            $calculation['installment_amount'],
            $calculation['frequency']
        );

        return array_merge($calculation, [
            'start_date' => $startDate->toDateString(),
            'schedules' => $schedules,
        ]);
    }

    /**
     * Generate schedule preview
     *
     * @param Carbon $startDate
     * @param int $totalInstallments
     * @param float $installmentAmount
     * @param string $frequency
     * @return array
     */
    private function generateSchedulePreview(
        Carbon $startDate,
        int $totalInstallments,
        float $installmentAmount,
        string $frequency
    ): array {
        $schedules = [];
        $currentDate = $startDate->copy();

        for ($i = 1; $i <= $totalInstallments; $i++) {
            if ($frequency === 'weekly') {
                $currentDate->addWeek();
            } else {
                $currentDate->addMonth();
            }

            $schedules[] = [
                'installment_number' => $i,
                'due_date' => $currentDate->toDateString(),
                'amount' => round($installmentAmount, 2),
            ];
        }

        return $schedules;
    }
}
