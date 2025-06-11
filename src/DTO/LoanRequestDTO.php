<?php

// src/DTO/LoanRequestDTO.php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class LoanRequestDTO
{
    #[Groups(['loan_request:read', 'loan_request:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [50000, 100000, 200000, 500000])]
    public int $amount;

    #[Groups(['loan_request:read', 'loan_request:write'])]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [15, 20, 25])]
    public int $duration;

    #[Groups(['loan_request:read', 'loan_request:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public string $name;

    #[Groups(['loan_request:read', 'loan_request:write'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Groups(['loan_request:read', 'loan_request:write'])]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/',
        message: 'Le numéro de téléphone doit être un numéro français valide'
    )]
    public string $phone;
}
