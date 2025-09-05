<?php

namespace App\Request\Role;

use App\Services\User\RoleService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRoleRequest extends BaseRequest {

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    #[NotBlank]
    #[NotNull]
    public array $permissions;


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected RoleService $roleService,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    // -----------------------------------------------------------
    /**
     * EN: FUNCTION TO VALIDATE THE REQUEST DATA
     * ES: FUNCIÓN PARA VALIDAR LOS DATOS DE LA PETICIÓN
     *
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function validate(): void
    {
        parent::validate();

        $roleChecker = $this->roleService->getRoleByName($this->name);

        if($roleChecker)
        {
            $this->addError('name', 'Ya existe un rol con el nombre introducido');
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------
}