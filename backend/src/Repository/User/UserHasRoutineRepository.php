<?php

namespace App\Repository\User;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineCategory;
use App\Entity\User\User;
use App\Entity\User\UserHasRoutine;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserHasRoutineRepository extends EntityRepository
{
    use DoctrineStorableObject;


    public function create(
        User $user,
        Routine $routine,
    ): UserHasRoutine {
        $userHasRoutine = new UserHasRoutine();
        $userHasRoutine->setUser($user);
        $userHasRoutine->setRoutine($routine);

       $this->save($this->_em, $userHasRoutine);

        return $userHasRoutine;
    }


    /**
     * @ES EDITAR 
     */
    public function edit(
        UserHasRoutine $userHasRoutine,
        User $user,
        Routine $routine
    ): UserHasRoutine {
        $userHasRoutine->setUser($user);
        $userHasRoutine->setRoutine($routine);

        $this->save($this->_em, $userHasRoutine);

        return $userHasRoutine;
    }

    /**
     * @ES ELIMINAR
     */
    public function remove(UserHasRoutine $userHasRoutine): void
    {
        $this->delete($this->_em, $userHasRoutine);
    }


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST ROUTINES
     * ES: FUNCIÓN PARA LISTAR EJERCICIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('uhr')
            ->leftJoin('uhr.routine', 'r')
            ->leftJoin('uhr.user', 'user')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'creator')
            ->leftJoin('creator.userRoles', 'creatorUserRoles') // <-- Añade esto
            ->leftJoin('creatorUserRoles.role', 'creatorRole') 
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->leftJoin('user.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->addSelect('routineCategory')
            ->addSelect('r')
            ->addSelect('user')
            ->addSelect('creator')
            ->addSelect('creatorUserRoles')
            ->addSelect('creatorRole')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
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

                $seenRoutines = [];

/*         foreach ($paginator as $verification) {
            $routineId = $verification['routine']['id'] ?? null;
            if ($routineId && !isset($seenRoutines[$routineId])) {
                $result[] = $verification;
                $seenRoutines[$routineId] = true;
            }
        } */

        $lastPage = (integer)ceil($totalRegisters / $filterService->limit);

        return [
            'totalRegisters' => $totalRegisters,
            'routines'    => $result,
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
                        $query->orderBy('r.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('r.name', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('r.description', $order['order']);
                        break;
                    case "routine_category":
                        $query->orderBy('routineCategory.name', $order['order']);
                        break;
                    case "active":
                        $query->orderBy('r.active', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('r.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('r.updatedAt', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('creator.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('r.createdAt', 'DESC');
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
                    $conditions[] = 'r.name LIKE :' . $param . ' OR r.description LIKE :' . $param;
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
                $query->andWhere('r.active = :active')
                    ->setParameter('active', $status);
            }

            $routineCategory = $filterService->getFilterValue('routine_category');
            if($routineCategory != null)
            {
                $query->andWhere('routineCategory.id IN (:routine_category)')
                    ->setParameter('routine_category', $routineCategory);
            }

            $exercises = $filterService->getFilterValue('exercises');
            if($exercises != null)
            {
                $query->andWhere('exercise.id IN (:exercises)')
                    ->setParameter('exercises', $exercises);
            }

            $user = $filterService->getFilterValue('user');
            if ($user != null) {
                $query->andWhere('user.id = :user')
                    ->setParameter('user', $user);
            }
/* 
            $isUser = $filterService->getFilterValue('isUser');
            if ($isUser != null) {
                $query->andWhere('user.id = :user OR userRoles.role = :adminRole OR userRoles.role = :superAdminRole')
                    ->setParameter('user', $isUser)
                    ->setParameter('adminRole', '2')
                    ->setParameter('superAdminRole', '1');
            } */
        
        
        
        
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|Routine|array
    {
        return $this->createQueryBuilder('uhr')
            ->leftJoin('uhr.routine', 'r')
            ->leftJoin('uhr.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('r.routineCategory')
            ->addSelect('r')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


        // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleRoutineById(string $id, ?bool $array = false): null|UserHasRoutine|array
    {
        $query = $this->createQueryBuilder('uhr')
            ->andWhere('uhr.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------



        /**
     * EN: FUNCTION TO FIND A ROUTINE BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN EJERCICIO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|Routine|array
    {
        return $this->createQueryBuilder('uhr')
            ->leftJoin('uhr.routine', 'r')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('routineCategory')
            ->addSelect('r')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------
}