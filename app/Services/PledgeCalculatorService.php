<?php

namespace App\Services;

class PledgeCalculatorService
{
    /**
     * Calculate pledge appraisal value and loan amount
     *
     * @param float $goldAmount
     * @param float $sellPrice
     * @param float $appraisalRate (default 85%)
     * @return array
     */
    public function calculateAppraisal(
        float $goldAmount,
        float $sellPrice,
        float $appraisalRate = 0.85
    ): array {
        $appraisalValue = $goldAmount * $sellPrice * $appraisalRate;
        $maxLoan = $appraisalValue; // Max loan is appraisal value

        return [
            'gold_amount' => $goldAmount,
            'sell_price' => $sellPrice,
            'appraisal_rate' => $appraisalRate,
            'appraisal_value' => round($appraisalValue, 2),
            'max_loan' => round($maxLoan, 2),
        ];
    }

    /**
     * Calculate total amount including fees
     *
     * @param float $loanAmount
     * @param int $durationMonths
     * @param float $adminFee
     * @param float $storageFeeRate (percentage per month)
     * @return array
     */
    public function calculateTotal(
        float $loanAmount,
        int $durationMonths,
        float $adminFee = 50000,
        float $storageFeeRate = 1.5
    ): array {
        $totalStorageFee = ($loanAmount * $storageFeeRate / 100) * $durationMonths;
        $totalAmount = $loanAmount + $adminFee + $totalStorageFee;
        $monthlyInstallment = $durationMonths > 0 ? $totalAmount / $durationMonths : 0;

        return [
            'loan_amount' => round($loanAmount, 2),
            'admin_fee' => round($adminFee, 2),
            'storage_fee_rate' => $storageFeeRate,
            'total_storage_fee' => round($totalStorageFee, 2),
            'total_amount' => round($totalAmount, 2),
            'duration_months' => $durationMonths,
            'monthly_installment' => round($monthlyInstallment, 2),
        ];
    }

    /**
     * Simulate pledge calculation
     *
     * @param array $params
     * @return array
     */
    public function simulate(array $params): array
    {
        $appraisal = $this->calculateAppraisal(
            $params['gold_amount'],
            $params['sell_price'],
            $params['appraisal_rate'] ?? 0.85
        );

        $loanAmount = min($params['loan_amount'] ?? $appraisal['max_loan'], $appraisal['max_loan']);

        $calculation = $this->calculateTotal(
            $loanAmount,
            $params['duration_months'],
            $params['admin_fee'] ?? 50000,
            $params['storage_fee_rate'] ?? 1.5
        );

        return array_merge($appraisal, $calculation);
    }
}
