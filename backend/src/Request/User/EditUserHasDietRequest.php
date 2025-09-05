<?php

namespace App\Request\User;

use App\Entity\User\Role;
use App\Services\User\UserPermissionService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

class EditUserHasDietRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: UserHasDiet::class, message: 'La rutina del usuario seleccionada no existe')]
    public string $userHasDietId;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: 'El usuario seleccionado no existe')]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Diet::class, message: 'La rutina seleccionada no existe')]
    public string $dietId;

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