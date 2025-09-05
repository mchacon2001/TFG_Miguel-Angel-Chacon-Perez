<?php

namespace App\Repository\Diet;

use App\Entity\Diet\Diet;
use App\Entity\Diet\DietHasFood;
use App\Entity\Food\Food;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DietHasFoodRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY ID
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|DietHasFood|array
    {
        return $this->createQueryBuilder('dhf')
            ->leftJoin('dhf.food', 'food')
            ->leftJoin('dhf.diet', 'diet')
            ->addSelect('food')
            ->addSelect('diet')
            ->andWhere('dhf.id = :id')
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
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleDietHasFoodById(string $id, ?bool $array = false): null|DietHasFood|array
    {
        return $this->createQueryBuilder('dhf')
            ->andWhere('dhf.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY Exercise IDENTIFIER, Routine, Exercise
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR CÓDIGO, PROVEEDOR
     *
     * @param string $exerciseIdentifier
     * @param string $routineId
     * @param string $exerciseId
     * @param bool|null $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByFoodIdentifierAndDietAndDiet(string $foodIdentifier, string $routineId, string $exerciseId, ?bool $array = false): null|DietHasFood|array
    {
        return $this->createQueryBuilder('dhf')
            ->leftJoin('dhf.food', 'food')
            ->leftJoin('dhf.diet', 'diet')
            ->addSelect('food')
            ->addSelect('diet')
            ->andWhere('dhf.foodIdentifier = :foodIdentifier')
            ->setParameter('foodIdentifier', $foodIdentifier)
            ->andWhere('diet.id = :routine')
            ->setParameter('routine', $routineId)
            ->andWhere('food.id = :exercise')
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
     * @param string $dietId
     * @param string $foodId
     * @param bool|null $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByDietAndFood(string $dietId, string $foodId, ?bool $array = false): null|DietHasFood|array
    {
        return $this->createQueryBuilder('dhf')
            ->leftJoin('dhf.food', 'food')
            ->leftJoin('dhf.diet', 'diet')
            ->addSelect('food')
            ->addSelect('diet')
            ->andWhere('diet.id = :diet')
            ->setParameter('diet', $dietId)
            ->andWhere('food.id = :food')
            ->setParameter('food', $foodId)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND THE RELATION BY Exercise
     * ES: FUNCIÓN PARA ENCONTRAR LA RELACIÓN POR Exercise
     *
     * @param string $FoodId
     * @param bool|null $array
     * @return DietHasFood|array|null
     */
    // --------------------------------------------------------------
    public function findByExercise(string $foodId, ?bool $array = false): null|DietHasFood|array
    {
        return $this->createQueryBuilder('dhf')
            ->leftJoin('dhf.food', 'food')
            ->leftJoin('dhf.diet', 'diet')
            ->addSelect('food')
            ->addSelect('diet')
            ->andWhere('food.id = :food')
            ->setParameter('food', $foodId)
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
        $query = $this->createQueryBuilder('dhf')
            ->leftJoin('dhf.diet', 'diet')
            ->leftJoin('dhf.food', 'food')
            ->addSelect('diet')
            ->addSelect('food');


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
                        $query->orderBy('dhf.id', $order['order']);
                        break;
                    case "diet":
                        $query->orderBy('diet.name', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('dhf.createdAt', $order['order']);
                        break;
                    case "food":
                        $query->orderBy('food.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('dhf.createdAt', 'DESC');
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

            $diet = $filterService->getFilterValue('diet');
            if($diet != null)
            {
                $query->andWhere('diet.id IN (:diet)')
                   ->setParameter('diet', $diet);
            }

            $saleDateRange = $filterService->getFilterValue('date');
            if($saleDateRange != null)
            {
                $from = DateTime::createFromFormat('Y-m-d', $saleDateRange['from'])->setTime(0,0,0);
                $to   = DateTime::createFromFormat('Y-m-d', $saleDateRange['to'])->setTime(23, 59, 59);


                $query->andWhere('dhf.createdAt BETWEEN :from AND :to')
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
     * @param Food $food
     * @param Diet $diet
     * @return DietHasFood|null
     */
    // --------------------------------------------------------------
    public function create(
        string $dayOfWeek,
        string $mealType,
        float $amount,
        ?string $notes,
        Food $food,
        Diet $diet,
    ): DietHasFood|null
    {
        $dietHasFood = (new DietHasFood())
            ->setFood($food)
            ->setDiet($diet)
            ->setDayOfWeek($dayOfWeek)
            ->setMealType($mealType)
            ->setAmount($amount)
            ->setNotes($notes)
            ->setCreatedAt(new DateTime())
            ->setUpdatedAt(new DateTime());

        $this->save($this->_em, $dietHasFood);

        return $dietHasFood;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A RELATION
     * ES: FUNCIÓN PARA ACTUALIZAR UNA RELACIÓN
     *
     * @param DietHasFood $dietHasFood
     * @param string $exerciseIdentifier
     * @param Food $food
     * @param Diet $diet
     * @return DietHasFood|null
     */
    // --------------------------------------------------------------
    public function edit(
        DietHasFood $dietHasFood,
        Food $food,
        Diet $diet
    ): DietHasFood|null
    {
        $dietHasFood
            ->setFood($food)
            ->setDiet($diet)
            ->setUpdatedAt(new DateTime('now'))
        ;

        $this->save($this->_em, $dietHasFood);
        return $dietHasFood;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A RELATION
     * ES: FUNCIÓN PARA BORRAR UNA RELACIÓN
     *
     * @param DietHasFood $dietHasFood
     * @return bool
     */
    // --------------------------------------------------------------
    public function remove(DietHasFood $dietHasFood): bool
    {
        $this->delete($this->_em, $dietHasFood);

        return true;
    }
    // --------------------------------------------------------------
}