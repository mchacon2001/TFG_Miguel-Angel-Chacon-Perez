<?php

namespace App\Repository\Exercise;

use App\Entity\Exercise\Exercise;
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

class ExerciseRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|Exercise|array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.exerciseCategories', 'exerciseCategories')
            ->leftJoin('e.user', 'user')
            ->addSelect('exerciseCategories')
            ->addSelect('user')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleExerciseById(string $id, ?bool $array = false): null|Exercise|array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN EXERCISE BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|Exercise|array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.exerciseCategories', 'exerciseCategories')
            ->leftJoin('e.user', 'user')
            ->addSelect('exerciseCategories')
            ->addSelect('user')
            ->andWhere('e.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST EXERCISES
     * ES: FUNCIÓN PARA LISTAR EJERCICIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('e')
            ->leftJoin('e.exerciseCategories', 'exerciseCategories')
            ->leftJoin('e.user', 'user')
            ->leftJoin('e.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exerciseOnRoutine')
            ->leftJoin('user.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->addSelect('exerciseCategories')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exerciseOnRoutine')
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
            'exercises'    => $result,
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
                        $query->orderBy('e.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('e.name', $order['order']);
                        break;
                    case "active":
                        $query->orderBy('e.active', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('e.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('e.updatedAt', $order['order']);
                        break;
                    case "exercise_category":
                        $query->orderBy('exerciseCategories.name', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('user.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('e.createdAt', 'DESC');
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
                    $conditions[] = 'e.name LIKE :' . $param;
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

            $exercise = $filterService->getFilterValue('id');
            if($exercise != null)
            {
                $query->andWhere('exerciseDeliveryNoteLine.id = :id')
                    ->setParameter('id', $exercise);
            }

            $status = $filterService->getFilterValue('active');
            if($status === 1 || $status === 0)
            {
                $query->andWhere('e.active = :active')
                    ->setParameter('active', $status);
            }

            $exerciseCategory = $filterService->getFilterValue('exercise_category');
            if($exerciseCategory != null)
            {
                $query->andWhere('exerciseCategories.id = :exercise_category')
                    ->setParameter('exercise_category', $exerciseCategory);
            }

            $dateRange = $filterService->getFilterValue('date');
            if($dateRange != null)
            {
                $from = DateTime::createFromFormat('Y-m-d', $dateRange['from'])->setTime(0,0,0);
                $to   = DateTime::createFromFormat('Y-m-d', $dateRange['to'])->setTime(23, 59, 59);

                $query->andWhere('deliveryNoteLines.createdAt BETWEEN :from AND :to')
                    ->setParameter('from', $from)
                    ->setParameter('to', $to);
            }

            $isUser = $filterService->getFilterValue('isUser');
            if ($isUser != null) {
                $query->andWhere('user.id = :user OR userRoles.role = :adminRole OR userRoles.role = :superAdminRole')
                    ->setParameter('user', $isUser)
                    ->setParameter('adminRole', '2')
                    ->setParameter('superAdminRole', '1');
            }
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE AN EXERCISE
     * ES: FUNCIÓN PARA CREAR UN EJERCICIO
     *
     * @param string $name
     * @param ExerciseCategory $exerciseCategory
     * @param User $user
     * @return Exercise|null
     */
    // --------------------------------------------------------------
    public function create(
        string $name,
        ExerciseCategory $exerciseCategory,
        User $user,
        ?string $description = null
    ): Exercise|null
    
    {
        $exercise = (new Exercise())
        ->setName($name)
        ->setExerciseCategories($exerciseCategory)
        ->setUser($user)
        ->setDescription($description)
        ->setCreatedAt(new DateTime('now'));
    
        ;

        $this->save($this->_em, $exercise);

        return $exercise;
    }
    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT AN EXERCISE
     * ES: FUNCIÓN PARA EDITAR UN EJERCICIO
     *
     * @param Exercise $exercise
     * @param string $name
     * @param ExerciseCategory $exerciseCategory
     * @return Exercise|null
     */
    // ----------------------------------------------------------------
    public function edit(
        Exercise $exercise,
        string $name,
        ExerciseCategory $exerciseCategory,
        ?string $description = null
    ): Exercise|null
    {
        $exercise
            ->setName($name)
            ->setExerciseCategories($exerciseCategory)
            ->setDescription($description)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $exercise);

        return $exercise;
    }
    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE AN EXERCISE
     * ES: FUNCIÓN PARA ELIMINAR UN EJERCICIO
     *
     * @param Exercise $exercise
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        Exercise $exercise
    ): null
    {
        $this->delete($this->_em, $exercise);

        return null;
    }
    // --------------------------------------------------------------
}