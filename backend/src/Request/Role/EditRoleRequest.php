<?php

namespace App\Request\Role;

use App\Entity\User\Role;
use App\Services\User\RoleService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditRoleRequest extends BaseRequest {

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Role::class, message: "El rol introducido no existe en la base de datos")]
    public string $roleId;

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description;

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
     * @throws NonUniqueResultException|JWTDecodeFailureException
     */
    // -----------------------------------------------------------
    public function validate(): void
    {
        parent::validate();

        $role = $this->roleService->getRoleById($this->roleId);

        if (!$role) {
            $this->addError("roleId", "El rol no existe");
            $this->resolveRequest();
        }

        $checkRoleByName = $this->roleService->getRoleByName($this->name);

        if ($checkRoleByName && $checkRoleByName->getId() !== $this->roleId) {
            $this->addError('name', 'Ya existe un rol con el nombre introducido');
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------
}