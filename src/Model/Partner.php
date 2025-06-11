<?php

// src/Model/Partner.php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Partner
{
    #[Groups(['partner:read', 'loan_rate:read'])]
    private string $id;

    #[Groups(['partner:read', 'loan_rate:read'])]
    private string $name;

    #[Groups(['partner:read'])]
    private string $code;

    #[Groups(['partner:read'])]
    private array $loanRates = [];

    public function __construct(string $id, string $name, string $code)
    {
        $this->id   = $id;
        $this->name = $name;
        $this->code = $code;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLoanRates(): array
    {
        return $this->loanRates;
    }

    public function setLoanRates(array $loanRates): self
    {
        $this->loanRates = $loanRates;

        return $this;
    }
}
