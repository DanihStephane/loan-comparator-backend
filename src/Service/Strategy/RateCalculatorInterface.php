<?php

// src/Service/Strategy/RateCalculatorInterface.php

namespace App\Service\Strategy;

interface RateCalculatorInterface
{
    public function calculateMonthlyPayment(int $amount, float $rate, int $durationYears): float;

    public function calculateTotalCost(int $amount, float $rate, int $durationYears): float;
}
