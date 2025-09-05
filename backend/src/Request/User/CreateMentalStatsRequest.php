<?php

namespace App\Request\User;

use App\Services\User\UserPermissionService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateMentalStatsRequest extends BaseRequest
{

    #[NotBlank]
    #[NotNull]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    public int $mood;

    #[NotBlank]
    #[NotNull]
    public float $sleepQuality;


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }
}