<?php

namespace App\Repository\Routine;

use App\Entity\Routine\RoutineHasExercise;
use App\Entity\Exercise\Exercise;
use App\Entity\Routine\RoutineRegister;
use App\Entity\Routine\RoutineRegisterExercises;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RoutineRegisterExercisesRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY ID
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|RoutineRegisterExercises|array
    {
        return $this->createQueryBuilder('rre')
            ->leftJoin('rre.exercise', 'exercise')
            ->leftJoin('rre.routineRegister', 'routines')
            ->addSelect('exercise')
            ->addSelect('routines')
            ->andWhere('rre.id = :id')
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
        $query = $this->createQueryBuilder('rre')
            ->leftJoin('rre.routineRegister', 'routines')
            ->leftJoin('rre.exercise', 'exercise')
            ->addSelect('routines')
            ->addSelect('exercise');


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
                        $query->orderBy('rre.id', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('rr.createdAt', $order['order']);
                        break;
                    case "exercise":
                        $query->orderBy('exercise.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('rre.createdAt', 'DESC');
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


                $query->andWhere('rre.createdAt BETWEEN :from AND :to')
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
     * @param Exercise $exercise
     * @param RoutineRegister $routineRegister
     * @param int $reps
     * @param float|null $weight
     * @return RoutineRegisterExercises|null
     */
    // --------------------------------------------------------------
    public function create(
        int $sets,
        int $reps,
        ?float $weight,
        Exercise $exercise,
        RoutineRegister $routineRegister,
    ): RoutineRegisterExercises|null
    {
        $routineRegisterExercises = (new RoutineRegisterExercises())
            ->setSets($sets)
            ->setReps($reps)
            ->setWeight($weight)
            ->setExercise($exercise)
            ->setRoutineRegister($routineRegister)
            ->setCreatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineRegisterExercises);

        return $routineRegisterExercises;
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A RELATION
     * ES: FUNCIÓN PARA ACTUALIZAR UNA RELACIÓN
     *
     * @param Exercise $exercise
     * @param RoutineRegisterExercises $routineRegisterExercises
     * @param RoutineRegister $routineRegister
     * @param int $reps
     * @param float|null $weight
     * @return RoutineRegisterExercises|null
     */
    // --------------------------------------------------------------
    public function edit(
        RoutineRegisterExercises $routineRegisterExercises,
        int $reps,
        ?float $weight
    ): RoutineRegisterExercises|null
    {
        $routineRegisterExercises
            ->setReps($reps)
            ->setWeight($weight)
        ;

        $this->save($this->_em, $routineRegisterExercises);

        return $routineRegisterExercises;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A RELATION
     * ES: FUNCIÓN PARA BORRAR UNA RELACIÓN
     *
     * @param RoutineRegisterExercises $routineRegisterExercises
     * @return void
     */
    // --------------------------------------------------------------
    public function remove(RoutineRegisterExercises $routineRegisterExercises): void
    {
        $this->delete($this->_em, $routineRegisterExercises);

    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: GET EXERCISE DETAILS BY DATE RANGE
     * ES: OBTENER DETALLES DE EJERCICIOS POR RANGO DE FECHAS
     */
    public function getExerciseDetailsByDateRange($user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->createQueryBuilder('rre')
            ->select('DATE(rre.createdAt) as date')
            ->addSelect('e.name as exercise_name')
            ->addSelect('rre.sets')
            ->addSelect('rre.reps')
            ->addSelect('rre.weight')
            ->leftJoin('rre.exercise', 'e')
            ->leftJoin('rre.routineRegister', 'rr')
            ->where('rr.user = :user')
            ->andWhere('rre.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('rre.createdAt', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    // --------------------------------------------------------------

}