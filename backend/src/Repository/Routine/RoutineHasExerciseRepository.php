<?php

namespace App\Repository\Routine;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineHasExercise;
use App\Entity\Exercise\Exercise;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class RoutineHasExerciseRepository extends EntityRepository
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
    public function findById(string $id, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->createQueryBuilder('rhe')
            ->leftJoin('rhe.exercise', 'exercise')
            ->leftJoin('rhe.routines', 'routines')
            ->addSelect('exercise')
            ->addSelect('routines')
            ->andWhere('rhe.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleRoutineHasExerciseById(string $id, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->createQueryBuilder('rhe')
            ->andWhere('rhe.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY EXERCISES IDENTIFIER, ROUTINE, EXERCISE
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR CÓDIGO, PROVEEDOR
     *
     * @param string $exerciseIdentifier
     * @param string $routineId
     * @param string $exerciseId
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByExerciseIdentifierAndRoutineAndExercise(string $exerciseIdentifier, string $routineId, string $exerciseId, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->createQueryBuilder('rhe')
            ->leftJoin('rhe.exercise', 'exercise')
            ->leftJoin('rhe.routines', 'routines')
            ->addSelect('exercise')
            ->addSelect('routines')
            ->andWhere('rhe.exerciseIdentifier = :exerciseIdentifier')
            ->setParameter('exerciseIdentifier', $exerciseIdentifier)
            ->andWhere('routines.id = :routine')
            ->setParameter('routine', $routineId)
            ->andWhere('exercise.id = :exercise')
            ->setParameter('exercise', $exerciseId)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY ROUTINE, EXERCISE
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR RUTINA, EJERCICIO
     *
     * @param string $routineId
     * @param string $exerciseId
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByRoutineAndExercise(string $routineId, string $exerciseId, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->createQueryBuilder('rhe')
            ->leftJoin('rhe.exercise', 'exercise')
            ->leftJoin('rhe.routines', 'routines')
            ->addSelect('exercise')
            ->addSelect('routines')
            ->andWhere('routines.id = :routine')
            ->setParameter('routine', $routineId)
            ->andWhere('exercise.id = :exercise')
            ->setParameter('exercise', $exerciseId)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY Exercise
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR Exercise
     *
     * @param string $exerciseId
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     */
    // --------------------------------------------------------------
    public function findByExercise(string $exerciseId, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->createQueryBuilder('rhe')
            ->leftJoin('rhe.exercise', 'exercise')
            ->leftJoin('rhe.routines', 'routines')
            ->addSelect('exercise')
            ->addSelect('routines')
            ->andWhere('exercise.id = :exercise')
            ->setParameter('exercise', $exerciseId)
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
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
        $query = $this->createQueryBuilder('rhe')
            ->leftJoin('rhe.routines', 'routines')
            ->leftJoin('rhe.exercise', 'exercise')
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
                        $query->orderBy('rhe.id', $order['order']);
                        break;
                    case "routine":
                        $query->orderBy('routine.name', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('rhe.createdAt', $order['order']);
                        break;
                    case "exercise":
                        $query->orderBy('exercise.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('rhe.createdAt', 'DESC');
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


                $query->andWhere('rhe.createdAt BETWEEN :from AND :to')
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
     * @param string $exerciseIdentifier
     * @param Exercise $exercise
     * @param Routine $routine
     * @return RoutineHasExercise|null
     */
    // --------------------------------------------------------------
    public function create(
        int $sets,
        int $reps,
        int $restTime,
        Exercise $exercise,
        Routine $routine,
        int $day,
    ): RoutineHasExercise|null
    {
        $routineHasExercise = (new RoutineHasExercise())
            ->setSets($sets)
            ->setReps($reps)
            ->setRestTime($restTime)
            ->setExercise($exercise)
            ->setRoutines($routine)
            ->setCreatedAt(new DateTime('now'))
            ->setDay($day)
        ;

        $this->save($this->_em, $routineHasExercise);

        return $routineHasExercise;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A RELATION
     * ES: FUNCIÓN PARA ACTUALIZAR UNA RELACIÓN
     *
     * @param RoutineHasExercise $routineHasExercise
     * @param string $exerciseIdentifier
     * @param Exercise $exercise
     * @param Routine $routine
     * @return RoutineHasExercise|null
     */
    // --------------------------------------------------------------
    public function edit(
        RoutineHasExercise $routineHasExercise,
        Exercise $exercise,
        Routine $routine
    ): RoutineHasExercise|null
    {
        $routineHasExercise
            ->setExercise($exercise)
            ->setRoutines($routine)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $routineHasExercise);

        return $routineHasExercise;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A RELATION
     * ES: FUNCIÓN PARA BORRAR UNA RELACIÓN
     *
     * @param RoutineHasExercise $routineHasExercise
     * @return bool
     */
    // --------------------------------------------------------------
    public function remove(RoutineHasExercise $routineHasExercise): bool
    {
        $this->delete($this->_em, $routineHasExercise);

        return true;
    }
    // --------------------------------------------------------------
}