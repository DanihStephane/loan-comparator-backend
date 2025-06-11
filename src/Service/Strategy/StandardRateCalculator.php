<?php

// src/Service/Strategy/StandardRateCalculator.php

namespace App\Service\Strategy;

class StandardRateCalculator implements RateCalculatorInterface
{
    public function calculateMonthlyPayment(int $amount, float $rate, int $durationYears): float
    {
        $monthlyRate      = ($rate / 100) / 12;
        $numberOfPayments = $durationYears * 12;

        if (0.0 === $monthlyRate) {
            return $amount / $numberOfPayments;
        }

        return ($amount * $monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) /
               (pow(1 + $monthlyRate, $numberOfPayments) - 1);
    }

    public function calculateTotalCost(int $amount, float $rate, int $durationYears): float
    {
        $monthlyPayment = $this->calculateMonthlyPayment($amount, $rate, $durationYears);

        return $monthlyPayment * $durationYears * 12;
    }
}
