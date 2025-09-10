<?php

namespace App\Repository\Routine;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineCategory;
use App\Entity\User\User;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RoutineCategoryRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE CATEGORY BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORÍA DE RUTINA POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|RoutineCategory|array
    {
        return $this->createQueryBuilder('rc')
            ->leftJoin('rc.routines', 'routines')
            ->leftJoin('rc.user', 'user')
            ->addSelect('routines')
            ->addSelect('user')
            ->andWhere('rc.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE CATEGORY BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORÍA DE RUTINA POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleRoutineCategoryById(string $id, ?bool $array = false): null|RoutineCategory|array
    {
        $query = $this->createQueryBuilder('rc')
            ->andWhere('rc.id = :id')
            ->setParameter('id', $id);


        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE CATEGORY BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORIA DE RUTINA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|RoutineCategory|array
    {
        return $this->createQueryBuilder('rc')
            ->leftJoin('rc.user', 'user')
            ->leftJoin('rc.routines', 'routines')
            ->addSelect('routines')
            ->addSelect('user')
            ->andWhere('rc.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST ROUTINE CATEGORIES
     * ES: FUNCIÓN PARA LISTAR CATEGORÍAS DE RUTINAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('rc')
            ->leftJoin('rc.user', 'user')
            ->leftJoin('rc.routines', 'routines')
            ->addSelect('routines')
            ->addSelect('user')
        ;

        $this->setFilters($query, $filterService);
        $this->setOrders($query, $filterService);

        $query->setFirstResult($filterService->page > 1 ? (($filterService->page - 1)*$filterService->limit) : $filterService->page - 1);
        $query->setMaxResults($filterService->limit);

        $paginator = new Paginator($query);
        $paginator->getQuery()->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);
        $totalRegisters = $paginator->count();

        $result = [];

        foreach ($paginator as $verification) {
            $result[] = $verification;
        }

        $lastPage = (integer)ceil($totalRegisters / $filterService->limit);

        return [
            'totalRegisters' => $totalRegisters,
            'routines'         => $result,
            'lastPage'       => $lastPage,
            'filters'        => $filterService->getAll()
        ];
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET ORDER
     * ES: FUNCIÓN PARA ESTABLECER ORDEN
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // --------------------------------------------------------------
    public function setOrders(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getOrders()) > 0) {
            foreach ($filterService->getOrders() as $order)
            {
                switch ($order['field'])
                {
                    case "id":
                        $query->orderBy('rc.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('rc.name', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('rc.description', $order['order']);
                        break;
                    case "active":
                        $query->orderBy('rc.active', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('rc.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('rc.updatedAt', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('user.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('rc.createdAt', 'DESC');
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO SET FILTERS
     * ES: FUNCIÓN PARA ESTABLECER FILTROS
     *
     * @param QueryBuilder $query
     * @param FilterService $filterService
     * @return void
     */
    // --------------------------------------------------------------
    public function setFilters(QueryBuilder $query, FilterService $filterService): void
    {
        if (count($filterService->getFilters()) > 0)
        {
            $search_array = $filterService->getFilterValue('search_array');
            if ($search_array != null)
            {
                $array_values = explode(' ', $search_array);

                $conditions = [];
                $parameters = [];
                
                foreach ($array_values as $index => $value)
                {
                    $param = 'search' . $index;
                    $conditions[] = 'rc.name LIKE :' . $param . ' OR rc.description LIKE :' . $param;
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

            $status = $filterService->getFilterValue('active');
            if($status === 1 || $status === 0)
            {
                $query->andWhere('rc.active = :active')
                    ->setParameter('active', $status);
            }
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A ROUTINE CATEGORY
     * ES: FUNCIÓN PARA CREAR UNA CATEGORÍA DE RUTINA
     *
     * @param string $name
     * @param string|null $description
     * @param User $user
     * @return RoutineCategory|null
     */
    // --------------------------------------------------------------
    public function create(
        string $name,
        ?string $description,
        User $user
    ): RoutineCategory|null
    {
        $routineCategory = (new RoutineCategory())
            ->setName($name)
            ->setDescription($description)
            ->setUser($user)
            ->setCreatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineCategory);

        return $routineCategory;
    }
    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT A ROUTINE CATEGORY
     * ES: FUNCIÓN PARA EDITAR UNA CATEGORÍA DE RUTINA
     *
     * @param RoutineCategory $routineCategory
     * @param string $name
     * @param string|null $description
     * @return RoutineCategory|null
     */
    // ----------------------------------------------------------------
    public function edit(
        RoutineCategory $routineCategory,
        string $name,
        ?string $description,
    ): RoutineCategory|null
    {
        $routineCategory
            ->setName($name)
            ->setDescription($description)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineCategory);

        return $routineCategory;
    }
    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A ROUTINE CATEGORY
     * ES: FUNCIÓN PARA ELIMINAR UNA CATEGORÍA DE RUTINA
     *
     * @param RoutineCategory $routineCategory
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        RoutineCategory $routineCategory
    ): null
    {
        $this->delete($this->_em, $routineCategory);

        return null;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO TOGGLE STATUS
     * ES: FUNCIÓN PARA CAMBIAR DE ESTADO
     *
     * @param RoutineCategory $routineCategory
     * @return RoutineCategory|null
     */
    // --------------------------------------------------------------
    public function toggleStatus(
        RoutineCategory $routineCategory
    ): RoutineCategory|null
    {
        $routineCategory
            ->setActive(!$routineCategory->getActive())
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineCategory);

        return $routineCategory;
    }
    // --------------------------------------------------------------
}