<?php

namespace App\Repository\Routine;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineHasExercise;
use App\Entity\Exercise\Exercise;
use App\Entity\Routine\RoutineRegister;
use App\Entity\User\User;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RoutineRegisterRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY ID
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return RoutineRegister|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|RoutineRegister|array
    {
        return $this->createQueryBuilder('rr')
            ->leftJoin('rr.user', 'user')
            ->addSelect('user')
            ->leftJoin('rr.routines', 'routine')
            ->addSelect('routine')
            ->leftJoin('rr.routineRegisterExercises', 'routineRegisterExercises')
            ->addSelect('routineRegisterExercises')
            ->leftJoin('routineRegisterExercises.exercise', 'exerciseRegistered')
            ->addSelect('exerciseRegistered')
            ->leftJoin('routine.routineHasExercise', 'routineHasExercise')
            ->addSelect('routineHasExercise')
            ->leftJoin('routineHasExercise.exercise', 'exercise')
            ->addSelect('exercise')
            ->andWhere('rr.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST
     * ES: FUNCIÓN PARA LISTAR 
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('rr')
            ->leftJoin('rr.user', 'user')
            ->leftJoin('rr.routines', 'routines')
            ->leftJoin('rr.routineRegisterExercises', 'routineRegisterExercises')
            ->addSelect('user')
            ->addSelect('routines')
            ->addSelect('routineRegisterExercises');


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
            'routineHasExercises'          => $result,
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
                        $query->orderBy('rr.id', $order['order']);
                        break;
                    case "routine":
                        $query->orderBy('routine.name', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('rr.createdAt', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('user.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('rr.createdAt', 'DESC');
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

            $routines = $filterService->getFilterValue('routines');
            if($routines != null)
            {
                $query->andWhere('routine.id IN (:routines)')
                    ->setParameter('routines', $routines);
            }

            $saleDateRange = $filterService->getFilterValue('date');
            if($saleDateRange != null)
            {
                $from = DateTime::createFromFormat('Y-m-d', $saleDateRange['from'])->setTime(0,0,0);
                $to   = DateTime::createFromFormat('Y-m-d', $saleDateRange['to'])->setTime(23, 59, 59);


                $query->andWhere('rr.createdAt BETWEEN :from AND :to')
                    ->setParameter('from', $from)
                    ->setParameter('to', $to);
            }
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A RELATION
     * ES: FUNCIÓN PARA CREAR UN RELACIÓN
     *
     * @param User $user
     * @param Routine $routine
     * @return RoutineRegister|null
     */
    // --------------------------------------------------------------
    public function create(
        User $user,
        Routine $routine,
        int $day
    ): RoutineRegister|null
    {
        $routineRegister = (new RoutineRegister())
            ->setUser($user)
            ->setRoutines($routine)
            ->setDay($day)
            ->setStartTime(new DateTime('now'))
            ->setCreatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineRegister);

        return $routineRegister;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A RELATION
     * ES: FUNCIÓN PARA ACTUALIZAR UNA RELACIÓN
     *
     * @param User $user
     * @param Routine $routine
     * @param int $startTime
     * @param int|null $endTime
     * @return RoutineRegister|null
     */
    // --------------------------------------------------------------
    public function edit(
        RoutineRegister $routineRegister,
        User $user,
        Routine $routine,
        int $startTime,
        int|null $endTime
    ): RoutineRegister|null
    {
        $routineRegister
            ->setUser($user)
            ->setRoutines($routine)
            ->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineRegister);

        return $routineRegister;
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A RELATION
     * ES: FUNCIÓN PARA BORRAR UNA RELACIÓN
     *
     * @param RoutineRegister $routineRegister
     * @return bool
     */
    // --------------------------------------------------------------
    public function remove(RoutineRegister $routineRegister): void
    {
        $this->delete($this->_em, $routineRegister);

    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FINISH A ROUTINE REGISTER
     * ES: FUNCIÓN PARA FINALIZAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegister $routineRegister
     * @return RoutineRegister|null
     */
    public function finish(RoutineRegister $routineRegister): ?RoutineRegister
    {
        $routineRegister
            ->setEndTime(new DateTime('now'));

        $this->save($this->_em, $routineRegister);

        return $routineRegister;
    }

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ACTIVE ROUTINE BY USER
     * ES: FUNCIÓN PARA OBTENER LA RUTINA ACTIVA POR USUARIO
     * @param User $user
     * @param Routine $routine
     * @return RoutineRegister|null
     */
    public function getActiveRoutineRegisterByUserAndRoutine(User $user, Routine $routine): ?RoutineRegister
    {
        return $this->createQueryBuilder('rr')
            ->leftJoin('rr.user', 'user')
            ->addSelect('user')
            ->leftJoin('rr.routines', 'routine')
            ->addSelect('routine')
            ->where('user = :user')
            ->andWhere('routine = :routine')
            ->andWhere('rr.endTime IS NULL')
            ->setParameter('user', $user)
            ->setParameter('routine', $routine)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    // --------------------------------------------------------------

    /**
     * EN: GET EXERCISE DAYS BY DATE RANGE
     * ES: OBTENER DÍAS DE EJERCICIO POR RANGO DE FECHAS
     */
    public function getExerciseDaysByDateRange($user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->createQueryBuilder('rr')
            ->select('DATE(rr.startTime) as date')
            ->addSelect('r.name as routine_name')
            ->addSelect('CASE WHEN rr.endTime IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, rr.startTime, rr.endTime) ELSE NULL END as duration')
            ->leftJoin('rr.routines', 'r')
            ->where('rr.user = :user')
            ->andWhere('rr.startTime BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('DATE(rr.startTime)', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    // --------------------------------------------------------------
}