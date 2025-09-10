<?php

namespace App\Request\User;

use App\Entity\User\User;
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

class ChangePasswordUserRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: 'El usuario introducido no existe en la base de datos')]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    #[Regex(
        pattern: '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}$/',
        message: 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos un número, una mayúscula y una minúscula.'
    )]
    public string $password;

    #[NotBlank]
    #[NotNull]
    public string $passwordConfirm;


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    public function validate(): void
    {
        parent::validate();

        $user = $this->userService->getUserByIdSimple($this->userId);

        if( !$user) {
            $this->addError("userId", "El usuario no existe");
            $this->resolveRequest();
        }

        if($this->password !== $this->passwordConfirm)
        {
            $this->addError('password', 'Las contraseñas introducidas no coinciden, por favor, introduzca las contraseñas de nuevo');
            $this->resolveRequest();
        }
    }
}