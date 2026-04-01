<?php

namespace App\Services;

use App\Models\AmortizationSchedule;
use App\Models\LoanApplication;
use Carbon\Carbon;

class AmortizationService
{

    public function calculateInterestRate($profile): float
    {
        $omzet = (float) $profile->omzet;
        $years = (int) preg_replace('/[^0-9]/', '', $profile->years_running);
        $employees = (int) $profile->employee_count;

        if ($omzet < 50000000) {
            $interest = 12.0;
        } elseif ($omzet <= 200000000) {
            $interest = 10.0;
        } else {
            $interest = 8.0;
        }

        if ($years < 1) {
            $interest += 2.0;
        }

        if ($employees >= 10) {
            $interest -= 1.0;
        }

        return $interest;
    }


    public function generateSchedule(LoanApplication $loan)
    {
        $p = (float) $loan->amount;
        $n = (int) $loan->tenor;
        $r = ($loan->interest_rate / 100) / 12;

        if ($r > 0) {
            $m = ($p * $r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
        } else {
        $m = $p / $n;
    }

    $remainingBalance = $p;
    $dueDate = Carbon::now();


        for ($i = 1; $i <= $n; $i++) {
            $interestPayment = $remainingBalance * $r;
            $principalPayment = $m - $interestPayment;

            if ($i === $n) {
                $principalPayment = $remainingBalance;
                $m = $principalPayment + $interestPayment;
            }

            $remainingBalance -= $principalPayment;
            if ($remainingBalance < 0) $remainingBalance = 0;

            $dueDate->addMonthNoOverflow();

        AmortizationSchedule::create([
            'loan_application_id' => $loan->id,
            'installment_number'  => $i,
            'principal_payment'   => round($principalPayment, 2),
            'interest_payment'    => round($interestPayment, 2),
            'total_payment'       => round($m, 2),
            'remaining_balance'   => round($remainingBalance, 2),
            'due_date'            => $dueDate->format('Y-m-d'),
        ]);
    }

        $loan->update([
            'monthly_installment' => round($m, 2),
            'total_interest'      => round(($m * $n) - $p, 2),
        'total_payment'       => round($m * $n, 2),
        ]);
    }
}
