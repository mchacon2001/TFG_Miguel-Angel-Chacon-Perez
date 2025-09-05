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

class RoutineRepository extends EntityRepository
{
    use DoctrineStorableObject;

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
        return $this->createQueryBuilder('r')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->leftJoin('r.userRoutines', 'userRoutines')
            ->leftJoin('userRoutines.user', 'userRoutinesUser')
            ->addSelect('userRoutinesUser')
            ->addSelect('routineCategory')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->addSelect('userRoutines')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // -------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A ROUTINE BY ID AND EXERCISES
     * ES: FUNCIÓN PARA ENCONTRAR UNA RUTINA POR ID Y EJERCICIOS
     *
     * @param string $exerciseId
     * @param bool|null $array
     * @return array
     */
    // --------------------------------------------------------------
    public function findAllRoutinesByExercise(string $exerciseId, ?bool $array = false): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->andWhere('exercise.id = :exercise')
            ->setParameter('exercise', $exerciseId)
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
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
    public function findSimpleRoutineById(string $id, ?bool $array = false): null|Routine|array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
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
    public function findRoutineByName(string $name, ?bool $array = false): null|Routine|array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.name = :name')
            ->setParameter('name', $name);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL ROUTINES
     * ES: FUNCIÓN PARA OBTENER TODOS LOS EJERCICIOS
     *
     * @param bool|null $array
     * @return array|Routine|null
     */
    // --------------------------------------------------------------
    public function getAllRoutines(?bool $array = false): array|Routine|null
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('routineCategory')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ROUTINE EXERCISES
     * ES: FUNCIÓN PARA OBTENER LOS EJERCICIOS DE UN EJERCICIO
     *
     * @param string $id
     * @param bool|null $array
     * @return array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function getRoutineExercises(string $id, ?bool $array = false): Routine|null|array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->andWhere('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
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
        return $this->createQueryBuilder('r')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('routineCategory')
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
     * EN: FUNCTION TO LIST ROUTINES
     * ES: FUNCIÓN PARA LISTAR EJERCICIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.routineCategory', 'routineCategory')
            ->leftJoin('r.user', 'user')
            ->leftJoin('r.routineHasExercise', 'routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->leftJoin('user.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->leftJoin('r.userRoutines', 'userRoutines')
            ->leftJoin('userRoutines.user', 'userRoutinesUser')
            ->addSelect('userRoutinesUser')
            ->addSelect('routineCategory')
            ->addSelect('user')
            ->addSelect('routineHasExercise')
            ->addSelect('exercise')
            ->addSelect('userRoutines')
            ->addSelect('userRoles')
            ->addSelect('role')
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
                        $query->orderBy('user.name', $order['order']);
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

            $isUser = $filterService->getFilterValue('isUser');
            if ($isUser != null) {
                $query->andWhere('user.id = :user OR userRoles.role = :adminRole OR userRoles.role = :superAdminRole')
                    ->setParameter('user', $isUser)
                    ->setParameter('adminRole', '2')
                    ->setParameter('superAdminRole', '1');
            }

            $orConditions = [];
                $parameters = [];

                $toGainMuscle = $filterService->getFilterValue('toGainMuscle');
                if ($toGainMuscle !== null) {
                    $orConditions[] = 'r.toGainMuscle = :toGainMuscle';
                    $parameters['toGainMuscle'] = (bool)$toGainMuscle;
                }

                $toLoseWeight = $filterService->getFilterValue('toLoseWeight');
                if ($toLoseWeight !== null) {
                    $orConditions[] = 'r.toLoseWeight = :toLoseWeight';
                    $parameters['toLoseWeight'] = (bool)$toLoseWeight;
                }

                $toMaintainWeight = $filterService->getFilterValue('toMaintainWeight');
                if ($toMaintainWeight !== null) {
                    $orConditions[] = 'r.toMaintainWeight = :toMaintainWeight';
                    $parameters['toMaintainWeight'] = (bool)$toMaintainWeight;
                }

                $toImprovePhysicalHealth = $filterService->getFilterValue('toImprovePhysicalHealth');
                if ($toImprovePhysicalHealth !== null) {
                    $orConditions[] = 'r.toImprovePhysicalHealth = :toImprovePhysicalHealth';
                    $parameters['toImprovePhysicalHealth'] = (bool)$toImprovePhysicalHealth;
                }

                $toImproveMentalHealth = $filterService->getFilterValue('toImproveMentalHealth');
                if ($toImproveMentalHealth !== null) {
                    $orConditions[] = 'r.toImproveMentalHealth = :toImproveMentalHealth';
                    $parameters['toImproveMentalHealth'] = (bool)$toImproveMentalHealth;
                }

                $fixShoulder = $filterService->getFilterValue('fixShoulder');
                if ($fixShoulder !== null) {
                    $orConditions[] = 'r.fixShoulder = :fixShoulder';
                    $parameters['fixShoulder'] = (bool)$fixShoulder;
                }

                $fixKnees = $filterService->getFilterValue('fixKnees');
                if ($fixKnees !== null) {
                    $orConditions[] = 'r.fixKnees = :fixKnees';
                    $parameters['fixKnees'] = (bool)$fixKnees;
                }

                $fixBack = $filterService->getFilterValue('fixBack');
                if ($fixBack !== null) {
                    $orConditions[] = 'r.fixBack = :fixBack';
                    $parameters['fixBack'] = (bool)$fixBack;
                }

                $rehab = $filterService->getFilterValue('rehab');
                if ($rehab !== null) {
                    $orConditions[] = 'r.rehab = :rehab';
                    $parameters['rehab'] = (bool)$rehab;
                }

                if (!empty($orConditions)) {
                    $orQuery = '(' . implode(' OR ', $orConditions) . ')';
                    $query->andWhere($orQuery);
                    foreach ($parameters as $key => $value) {
                        $query->setParameter($key, $value);
                    }
                }
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A ROUTINE
     * ES: FUNCIÓN PARA CREAR UN EJERCICIO
     *
     * @param string $name
     * @param string $description
     * @param RoutineCategory $routineCategory
     * @param User|null $user
     * @param int $quantity
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Routine|null
     */
    // --------------------------------------------------------------
    public function create(
        string $name,
        string $description,
        RoutineCategory $routineCategory,
        ?User $user,
        int $quantity,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Routine|null
    {
        $routine = new Routine();

        $routine
            ->setName($name)
            ->setDescription($description)
            ->setRoutineCategory($routineCategory)
            ->setUser($user)
            ->setCreatedAt(new DateTime('now'))
            ->setQuantity($quantity)
            ->setToGainMuscle($toGainMuscle)
            ->setToLoseWeight($toLoseWeight)
            ->setToMaintainWeight($toMaintainWeight)
            ->setToImprovePhysicalHealth($toImprovePhysicalHealth)
            ->setToImproveMentalHealth($toImproveMentalHealth)
            ->setFixShoulder($fixShoulder)
            ->setFixKnees($fixKnees)
            ->setFixBack($fixBack)
            ->setRehab($rehab)
        ;

        $this->save($this->_em, $routine);

        return $routine;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A ROUTINE
     * ES: FUNCIÓN PARA ACTUALIZAR UN EJERCICIO
     *
     * @param Routine $routine
     * @param string $name
     * @param string $description
     * @param RoutineCategory $routineCategory
     * @param int $quantity
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Routine|null
     */
    // --------------------------------------------------------------
    public function edit(
        Routine $routine,
        string $name,
        string $description,
        RoutineCategory $routineCategory,
        int $quantity,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Routine|null
    {
        $routine
            ->setName($name)
            ->setDescription($description)
            ->setRoutineCategory($routineCategory)
            ->setUpdatedAt(new DateTime('now'))
            ->setQuantity($quantity)
            ->setToGainMuscle($toGainMuscle)
            ->setToLoseWeight($toLoseWeight)
            ->setToMaintainWeight($toMaintainWeight)
            ->setToImprovePhysicalHealth($toImprovePhysicalHealth)
            ->setToImproveMentalHealth($toImproveMentalHealth)
            ->setFixShoulder($fixShoulder)
            ->setFixKnees($fixKnees)
            ->setFixBack($fixBack)
            ->setRehab($rehab)
        ;

        $this->save($this->_em, $routine);

        return $routine;
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A ROUTINE
     * ES: FUNCIÓN PARA ELIMINAR UN EJERCICIO
     *
     * @param Routine $routine
     * @return Routine|null
     */
    // --------------------------------------------------------------
    public function remove(Routine $routine): Routine|null
    {
        $this->delete($this->_em, $routine);

        return $routine;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO TOGGLE A ROUTINE STATUS
     * ES: FUNCIÓN PARA CAMBIAR EL ESTADO DE UN EJERCICIO
     *
     * @param Routine $routine
     * @return Routine|null
     */
    // --------------------------------------------------------------
    public function toggleStatus(Routine $routine): Routine|null
    {
        $routine
            ->setActive(!$routine->isActive())
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routine);

        return $routine;
    }
    // --------------------------------------------------------------

}