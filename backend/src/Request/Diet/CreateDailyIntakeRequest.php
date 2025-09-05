<?php

namespace App\Request\Diet;

use App\Services\User\UserPermissionService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User\User;

class CreateDailyIntakeRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: 'El usuario seleccionado no existe')]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    public array $meals = [];


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }


    /**
     * @throws NonUniqueResultException
     */
    public function validate(): void
    {
        parent::validate();
    }
}