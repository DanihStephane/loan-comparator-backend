<?php

// src/Model/LoanRate.php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class LoanRate
{
    #[Groups(['loan_rate:read'])]
    private string $id;

    #[Groups(['loan_rate:read'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private int $amount;

    #[Groups(['loan_rate:read'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [15, 20, 25])]
    private int $duration;

    #[Groups(['loan_rate:read'])]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private float $rate;

    #[Groups(['loan_rate:read'])]
    private Partner $partner;

    public function __construct(
        string $id,
        int $amount,
        int $duration,
        float $rate,
        Partner $partner,
    ) {
        $this->id       = $id;
        $this->amount   = $amount;
        $this->duration = $duration;
        $this->rate     = $rate;
        $this->partner  = $partner;
    }

    // Getters...
    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getPartner(): Partner
    {
        return $this->partner;
    }
}
