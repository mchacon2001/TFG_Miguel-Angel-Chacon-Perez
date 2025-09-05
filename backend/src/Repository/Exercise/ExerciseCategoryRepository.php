<?php

namespace App\Repository\Exercise;

use App\Entity\Exercise\ExerciseCategory;
use App\Entity\User\User;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ExerciseCategoryRepository extends EntityRepository
{
    use DoctrineStorableObject;


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE CATEGORY BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORÍA DE EJERCICIO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|ExerciseCategory|array
    {
        return $this->createQueryBuilder('ec')
            ->leftJoin('ec.user', 'user')
            ->leftJoin('ec.exercises', 'exercises')
            ->addSelect('user')
            ->addSelect('exercises')
            ->andWhere('ec.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE CATEGORY BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORÍA DE EJERCICIO POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleExerciseCategoryById(string $id, ?bool $array = false): null|ExerciseCategory|array
    {
        $query = $this->createQueryBuilder('ec')
            ->andWhere('ec.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE CATEGORY BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UNA CATEGORÍA DE EJERCICIO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|ExerciseCategory|array
    {
        return $this->createQueryBuilder('ec')
            ->leftJoin('ec.user', 'user')
            ->leftJoin('ec.exercises', 'exercises')
            ->addSelect('user')
            ->addSelect('exercises')
            ->andWhere('ec.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

        // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST SUPPLIERS
     * ES: FUNCIÓN PARA LISTAR PROVEEDORES
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('ec')
            ->leftJoin('ec.user', 'user')
            ->leftJoin('ec.exercises', 'exercises')
            ->addSelect('user')
            ->addSelect('exercises')
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
            'exerciseCategories'      => $result,
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
                        $query->orderBy('ec.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('ec.name', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('ec.description', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('ec.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('ec.updatedAt', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('ec.createdAt', 'DESC');
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
                    $conditions[] = 'ec.name LIKE :' . $param . ' OR ec.description LIKE :' . $param;
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
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE AN EXERCISE CATEGORY
     * ES: FUNCIÓN PARA CREAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param string $name
     * @param string|null $description
     * @param User $user
     * @return ExerciseCategory|null
     */
    // --------------------------------------------------------------
    public function create(
        string $name,
        ?string $description,
        User $user
    ): ExerciseCategory|null
    {
        $exerciseCategory = (new ExerciseCategory())
            ->setName($name)
            ->setDescription($description)
            ->setUser($user)
            ->setCreatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $exerciseCategory);

        return $exerciseCategory;
    }
    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT AN EXERCISE CATEGORY
     * ES: FUNCIÓN PARA EDITAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param ExerciseCategory $exerciseCategory
     * @param string $name
     * @param string|null $description
     * @return ExerciseCategory|null
     */
    // ----------------------------------------------------------------
    public function edit(
        ExerciseCategory $exerciseCategory,
        string $name,
        ?string $description,
    ): ExerciseCategory|null
    {
        $exerciseCategory
            ->setName($name)
            ->setDescription($description)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $exerciseCategory);

        return $exerciseCategory;
    }
    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE AN EXERCISE CATEGORY
     * ES: FUNCIÓN PARA ELIMINAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param ExerciseCategory $exerciseCategory
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        ExerciseCategory $exerciseCategory
    ): null
    {
        $this->delete($this->_em, $exerciseCategory);

        return null;
    }
    // --------------------------------------------------------------
}