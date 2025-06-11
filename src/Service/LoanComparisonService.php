<?php

// src/Service/LoanComparisonService.php

namespace App\Service;

use App\Model\LoanRate;
use App\Repository\LoanRateRepository;
use App\Service\Strategy\RateCalculatorInterface;

class LoanComparisonService
{
    public function __construct(
        private readonly LoanRateRepository $loanRateRepository,
        private readonly RateCalculatorInterface $rateCalculator,
    ) {
    }

    public function findBestRates(int $amount, int $duration): array
    {
        $rates = $this->loanRateRepository->findByAmountAndDuration($amount, $duration);

        return array_map(function (LoanRate $rate) {
            return [
                'partner'        => $rate->getPartner()->getName(),
                'rate'           => $rate->getRate(),
                'monthlyPayment' => $this->rateCalculator->calculateMonthlyPayment(
                    $rate->getAmount(),
                    $rate->getRate(),
                    $rate->getDuration()
                ),
                'totalCost' => $this->rateCalculator->calculateTotalCost(
                    $rate->getAmount(),
                    $rate->getRate(),
                    $rate->getDuration()
                ),
            ];
        }, $rates);
    }
}
