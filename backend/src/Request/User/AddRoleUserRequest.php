<?php

namespace App\Request\User;

use App\Entity\User\Role;
use App\Entity\User\User;
use App\Services\User\RoleService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddRoleUserRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: "El usuario introducido no existe en la base de datos")]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Role::class, message: "El rol introducido no existe en la base de datos")]
    public string $roleId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService,
                                protected RoleService $roleService)
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
        $role = $this->roleId ? $this->roleService->getRoleById($this->roleId) : null;

        if(!$role)
        {
            $this->addError("roleId", "El rol no existe");
            $this->resolveRequest();
        }

        if( !$user) {
            $this->addError("userId", "El usuario no existe");
            $this->resolveRequest();
        }
    }
}