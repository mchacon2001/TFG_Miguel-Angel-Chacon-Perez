<?php

namespace App\Services\User;

use App\Entity\Permission\Permission;
use App\Entity\User\Role;
use App\Repository\Permission\PermissionRepository;
use App\Repository\User\RoleRepository;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class RoleService
{
    protected RoleRepository|EntityRepository $roleRepository;
    protected PermissionRepository|EntityRepository $permissionRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->roleRepository = $em->getRepository(Role::class);
        $this->permissionRepository = $em->getRepository(Permission::class);
    }

    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A ROLE BY ID
     * ES: SERVICIO PARA OBTENER UN ROL POR ID
     *
     * @param int $roleId
     * @param bool|null $array
     * @return Role|array|null
     * @throws NonUniqueResultException
     */
    // ----------------------------------------------------------------
    public function getRoleById(int $roleId, ?bool $array = false): null|Role|array
    {
        return $this->roleRepository->findById($roleId, $array);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A ROLE BY NAME
     * ES: SERVICIO PARA OBTENER UN ROL POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Role|array|null
     * @throws NonUniqueResultException
     */
    // ----------------------------------------------------------------
    public function getRoleByName(string $name, ?bool $array = false): null|Role|array
    {
        return $this->roleRepository->findByName($name, $array);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL THE ROLES
     * ES: SERVICIO PARA OBTENER TODOS LOS ROLES
     *
     * @param bool $array
     * @return array
     */
    // ----------------------------------------------------------------
    public function getAllRoles(bool $array = false): array
    {
        return $this->roleRepository->getAllRoles($array, true);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL THE ROLES EXCEPT SUPER ADMIN
     * ES: SERVICIO PARA OBTENER TODOS LOS ROLES EXCEPTO SUPER ADMIN
     *
     * @param bool $array
     * @return array
     */
    // ----------------------------------------------------------------
    public function getAllRolesExceptSuperAdmin(bool $array = false): array
    {
        return $this->roleRepository->getAllMutableRoles($array, false);
    }
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST ALL THE ROLES
     * ES: SERVICIO PARA LISTAR TODOS LOS ROLES
     *
     * @param FilterService $filterService
     * @return array
     */
    // ----------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        return $this->roleRepository->list($filterService);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A ROLE
     * ES: SERVICIO PARA CREAR UN ROL
     *
     * @param string $name
     * @param string|null $description
     * @param array $permissions
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function create(string $name, ?string $description, array $permissions): ?Role
    {
        $permissions = $permissions ? $this->permissionRepository->findByIds($permissions) : [];

        return $this->roleRepository->create(
            name: $name,
            description: $description,
            permissions: $permissions
        );
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROLE
     * ES: SERVICIO PARA EDITAR UN ROL
     *
     * @param Role $role
     * @param string $name
     * @param string|null $description
     * @param array $permissionsArray
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function edit(Role $role, string $name, ?string $description, array $permissionsArray): ?Role
    {
        $permissions = $permissionsArray ? $this->permissionRepository->findByIds($permissionsArray) : [];

        return $this->roleRepository->edit(
            role: $role,
            name: $name,
            description: $description,
            permissions: $permissions
        );
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROLE'S PERMISSIONS
     * ES: SERVICIO PARA EDITAR LOS PERMISOS DE UN ROL
     *
     * @param Role $role
     * @param array|null $permissions
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function editPermissions(Role $role, ?array $permissions): ?Role
    {
        $permissions = $permissions ? $this->permissionRepository->findByIds($permissions) : [];

        $role = $this->roleRepository->removePermissions(
            $role
        );

        return $this->roleRepository->addPermissionsToRole(
            $role,
            $permissions
        );
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO TOGGLE A ROLE
     * ES: SERVICIO PARA TOGGLEAR UN ROL
     *
     * @param Role $role
     * @return Role|string|null
     */
    // ----------------------------------------------------------------
    public function toggle(Role $role): Role|null|string
    {
        return $this->roleRepository->toggleRole(
            $role
        );
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A ROLE
     * ES: SERVICIO PARA ELIMINAR UN ROL
     *
     * @param Role $role
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function delete(Role $role): ?Role
    {
        return $this->roleRepository->remove($role);
    }
    // ----------------------------------------------------------------
}