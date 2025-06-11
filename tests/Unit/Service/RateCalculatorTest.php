<?php
// tests/Unit/Service/RateCalculatorTest.php
namespace App\Tests\Unit\Service;

use App\Service\Strategy\StandardRateCalculator;
use PHPUnit\Framework\TestCase;

class RateCalculatorTest extends TestCase
{
    private StandardRateCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new StandardRateCalculator();
    }

    public function testCalculateMonthlyPayment(): void
    {
        $amount = 100000;
        $rate = 3.5;
        $duration = 20;

        $monthlyPayment = $this->calculator->calculateMonthlyPayment($amount, $rate, $duration);

        $this->assertEqualsWithDelta(579.96, $monthlyPayment, 0.01);
    }

    public function testCalculateTotalCost(): void
    {
        $amount = 100000;
        $rate = 3.5;
        $duration = 20;

        $totalCost = $this->calculator->calculateTotalCost($amount, $rate, $duration);

        $this->assertEqualsWithDelta(139190.4, $totalCost, 0.1);
    }
}