<?php

namespace App\Repository\User;

use App\Entity\User\Role;
use App\Entity\User\RoleHasPermission;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RoleRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROLE BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN ROL POR ID
     *
     * @param int $roleId
     * @param bool $array
     * @return Role|array|null
     * @throws NonUniqueResultException
     */
    // ----------------------------------------------------------------
    public function findById(int $roleId, ?bool $array = false): null|Role|array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.permissions', 'permissions')
            ->leftJoin('permissions.permission', 'permission')
            ->addSelect('permissions')
            ->addSelect('permission')
            ->andWhere('r.id = :id')
            ->setParameter('id',  $roleId )
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);

    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROLE BY NAME 
     * ES: FUNCIÓN PARA ENCONTRAR UN ROL POR NOMBRE
     *
     * @param string $name
     * @param bool $array
     * @return Role|array|null
     * @throws NonUniqueResultException
     */
    // ----------------------------------------------------------------
    public function findByName(string $name, bool $array = false): null|Role|array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name );

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL THE ROLES
     * ES: FUNCIÓN PARA OBTENER TODOS LOS ROLES
     *
     * @param bool $array
     * @param bool $includeSuperAdmin
     * @return array
     */
    // ----------------------------------------------------------------
    public function getAllRoles(bool $array = false, bool $includeSuperAdmin = true) : array
    {
        $query = $this->createQueryBuilder('r')
            ->addSelect('r')
        ;

        if(!$includeSuperAdmin){
            $query->andWhere('r.id != :superadminRole')
                ->setParameter('superadminRole', Role::ROLE_SUPER_ADMIN);
        }

        return $query->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL THE ROLES MUTABLES
     * ES: FUNCIÓN PARA OBTENER TODOS LOS ROLES MUTABLES
     *
     * @param bool $array
     * @return array
     */
    // ----------------------------------------------------------------
    public function getAllMutableRoles(bool $array = false) : array
    {
        $query = $this->createQueryBuilder('r');

        return $query->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // ----------------------------------------------------------------

    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST THE ROLES
     * ES: FUNCIÓN PARA LISTAR LOS ROLES
     *
     * @param FilterService $filterService
     * @return array
     */
    // ----------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere( 'r.immutable = 0')
        ;


        $this->setFilters($query, $filterService);
        $this->setOrders($query, $filterService);

        $query->setFirstResult($filterService->page > 1 ? (($filterService->page - 1)*$filterService->limit) : $filterService->page - 1);
        $query->setMaxResults($filterService->limit);

        $paginator = new Paginator($query);
        $paginator->getQuery()->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);
        $totalRegisters = $paginator->count();

        $result         = [];

        foreach ($paginator as $verification)
        {
            $result[] = $verification;
        }

        $lastPage = (integer)ceil($totalRegisters / $filterService->limit);

        return [
            'totalRegisters' => $totalRegisters,
            'roles'          => $result,
            'lastPage'       => $lastPage,
            'filters'        => $filterService->getAll(),
        ];
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET ORDERS
     * ES: FUNCIÓN PARA ESTABLECER LOS ORDENAMIENTOS
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // ----------------------------------------------------------------
    public function setOrders(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getOrders()) > 0) {
            foreach ($filterService->getOrders() as $order) {
                switch ($order['field']) {
                    case "id":
                        $query->orderBy('r.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('r.name', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('r.description', $order['order']);
                        break;
                    case "active":
                        $query->orderBy('r.active', $order['order']);
                        break;
                }
            }
        } else {
            $query->orderBy('r.name', 'DESC');
        }
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET FILTERS
     * ES: FUNCIÓN PARA ESTABLECER LOS FILTROS
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // ----------------------------------------------------------------
    public function setFilters(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getFilters()) > 0)
        {
            $search_array = $filterService->getFilterValue('search_array');
            if ($search_array != null) {
                $array_values = explode(' ', $search_array);

                $conditions = [];
                $parameters = [];

                foreach ($array_values as $index => $value)
                {
                    $param = 'search' . $index;
                    $conditions[] = 'r.name LIKE :' . $param;
                    $parameters[$param] = '%' . $value . '%';
                }

                if (!empty($conditions))
                {
                    $query->andWhere(implode(' AND ', $conditions));

                    foreach($parameters as $key => $value)
                    {
                        $query->setParameter($key, $value);
                    }
                }
            }

            $active = $filterService->getFilterValue('active');
            if($active === 1 || $active === 0)
            {
                $query->andWhere('r.active = :active')
                    ->setParameter('active', $active);
            }
        }
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A ROLE
     * ES: FUNCIÓN PARA CREAR UN ROL
     *
     * @param string $name
     * @param string|null $description
     * @param array $permissions
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function create(
        string $name,
        ?string $description,
        array $permissions
    ): ?Role
    {
        $role = (new Role())
            ->setName($name)
            ->setDescription($description)
        ;

        $this->addPermissionsToRole($role, $permissions);

        $this->save($this->_em, $role);

        return $role;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT A ROLE
     * ES: FUNCIÓN PARA EDITAR UN ROL
     *
     * @param Role $role
     * @param string $name
     * @param string|null $description
     * @param array $permissions
     * @return Role|null
     */
    // ----------------------------------------------------------------
    public function edit(
        Role $role,
        string $name,
        ?string $description,
        array $permissions
    ): ?Role
    {
        $role
            ->setName($name)
            ->setDescription($description)
        ;

        $this->removePermissions($role);
        $this->addPermissionsToRole($role, $permissions);

        $this->save($this->_em, $role);

        return $role;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A ROLE
     * ES: FUNCIÓN PARA ELIMINAR UN ROL
     *
     * @param Role $role
     * @return null
     */
    // ----------------------------------------------------------------
    public function remove(
        Role $role
    ): null
    {

        $this->delete($this->_em, $role);

        return null;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO ADD PERMISSIONS TO A ROLE
     * ES: FUNCIÓN PARA AGREGAR PERMISOS A UN ROL
     *
     * @param Role $role
     * @param array $permissions
     * @return Role
     */
    // ----------------------------------------------------------------
    public function addPermissionsToRole(Role $role, array $permissions): Role
    {

        foreach ($permissions as $permission) {

            $roleHasPermission = (new RoleHasPermission())
                ->setRole($role)
                ->setPermission($permission);

            $role->addPermission($roleHasPermission);

        }

        $this->save($this->_em, $role);

        return $role;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE PERMISSIONS FROM A ROLE
     * ES: FUNCIÓN PARA ELIMINAR PERMISOS DE UN ROL
     *
     * @param Role $role
     * @return Role
     */
    // ----------------------------------------------------------------
    public function removePermissions(Role $role): Role
    {
        foreach ($role->getPermissions() as $permission) {
            $role->removePermission($permission);

        }

        $this->save($this->_em, $role);

        return $role;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO TOGGLE A ROLE
     * ES: FUNCIÓN PARA ACTIVAR O DESACTIVAR UN ROL
     *
     * @param Role $role
     * @return Role|string|null
     */
    // ----------------------------------------------------------------
    public function toggleRole(Role $role): Role|null|string
    {
        $role->setActive(!$role->isActive());

        if($role->isActive() === true)
        {
            $message = 'activado';
        }
        else
        {
            $message = 'desactivado';
        }

        $this->save($this->_em, $role);

        return $message;
    }
    // ----------------------------------------------------------------
}