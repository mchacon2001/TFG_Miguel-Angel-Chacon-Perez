<?php


namespace App\Services\Permission;


use App\Entity\Permission\Permission;
use App\Entity\Permission\PermissionGroup;
use App\Repository\Permission\PermissionGroupRepository;
use App\Repository\Permission\PermissionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class PermissionService extends AbstractController
{

    private PermissionRepository|EntityRepository $permissionRepository;
    private PermissionGroupRepository|EntityRepository $permissionGroupRepository;


    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->permissionRepository      = $entityManager->getRepository(Permission::class);
        $this->permissionGroupRepository      = $entityManager->getRepository(PermissionGroup::class);
    }

    // -----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A PERMISSION BY ID
     * ES: SERVICIO PARA OBTENER UN PERMISO POR ID
     *
     * @param string $id
     * @return Permission|null
     */
    // -----------------------------------------------------------------
    public function getPermissionById(string $id): ?Permission
    {
        return $this->permissionRepository->find($id);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL AVAILABLE PERMISSIONS IN THE PLATFORM
     * ES: SERVICIO PARA OBTENER TODOS LOS PERMISOS DISPONIBLES EN LA PLATAFORMA
     *
     * @param bool $superAdminPermissions
     * @param bool $array
     * @return array|null
     */
    // -----------------------------------------------------------------
    public function getAvailablePermissions(bool $superAdminPermissions, bool $array = false): ?array
    {
        if($superAdminPermissions){
            $permissions = $this->permissionGroupRepository->getAvailablePermission($array);

        }else{
            $permissions = $this->permissionGroupRepository->getAvailablePermissionForNonSuperadmin($array);
        }
        return $permissions;
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL PERMISSIONS
     * ES: SERVICIO PARA OBTENER TODOS LOS PERMISOS
     *
     * @return array|null
     */
    // -----------------------------------------------------------------
    public function getAll():?array
    {
        return $this->permissionRepository->findAll();
    }
    // -----------------------------------------------------------------
}
