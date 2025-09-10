<?php

namespace App\Request\User;

use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreatePhysicalStatsRequest extends BaseRequest
{

    #[NotBlank]
    #[NotNull]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    public int $weight;

    #[NotBlank]
    #[NotNull]
    public float $height;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }
}