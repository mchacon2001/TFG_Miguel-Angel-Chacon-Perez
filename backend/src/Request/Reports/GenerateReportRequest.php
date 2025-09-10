<?php

namespace App\Request\Reports;

use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenerateReportRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Choice(choices: ['weekly', 'monthly', 'yearly'])]
    public string $period;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager)
    {
        parent::__construct($validator, $token, $jwtManager);
    }
}
