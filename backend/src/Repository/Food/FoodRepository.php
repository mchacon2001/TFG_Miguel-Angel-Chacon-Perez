<?php

namespace App\Repository\Food;

use App\Entity\Food\Food;
use App\Entity\User\User;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class FoodRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A FOOD ITEM BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN ALIMENTO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|Food|array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.user', 'user')
            ->addSelect('user')
            ->andWhere('f.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A FOOD ITEM BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UN ALIMENTO POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleFoodById(string $id, ?bool $array = false): null|Food|array
    {
        $query = $this->createQueryBuilder('f')
            ->andWhere('f.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A FOOD ITEM BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UN ALIMENTO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|Food|array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.user', 'user')
            ->addSelect('user')
            ->andWhere('f.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    public function findOneByName(string $name, ?bool $array = false): Food|array|null
{
    return $this->createQueryBuilder('f')
        ->leftJoin('f.user', 'user')
        ->addSelect('user')
        ->andWhere('f.name = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
}


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST FOOD ITEMS
     * ES: FUNCIÓN PARA LISTAR ALIMENTOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('f')
            ->leftJoin('f.user', 'user')
            ->leftJoin('f.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'foodOnDiet')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('foodOnDiet')
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
            'food'           => $result,
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
                        $query->orderBy('f.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('f.name', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('user.name', $order['order']);
                        break;
                    case "calories":
                        $query->orderBy('f.calories', $order['order']);
                        break;
                    case "proteins":
                        $query->orderBy('f.proteins', $order['order']);
                        break;
                    case "carbs":
                        $query->orderBy('f.carbs', $order['order']);
                        break;
                    case "fats":
                        $query->orderBy('f.fats', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('f.name', 'DESC');
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
                    $conditions[] = 'f.name LIKE :' . $param;
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

            $food = $filterService->getFilterValue('id');
            if($food != null)
            {
                $query->andWhere('f.id = :id')
                ->setParameter('id', $food);

            }

            $calories_min = $filterService->getFilterValue('calories_min');
            if ($calories_min !== null && $calories_min !== '') {
                $query->andWhere('f.calories >= :calories_min')
                    ->setParameter('calories_min', (int)$calories_min);
            }

            $calories_max = $filterService->getFilterValue('calories_max');
            if ($calories_max !== null && $calories_max !== '') {
                $query->andWhere('f.calories <= :calories_max')
                    ->setParameter('calories_max', (int)$calories_max);
            }

            $proteins_min = $filterService->getFilterValue('proteins_min');
            if ($proteins_min !== null && $proteins_min !== '') {
                $query->andWhere('f.proteins >= :proteins_min')
                    ->setParameter('proteins_min', (float)$proteins_min);
            }

            $proteins_max = $filterService->getFilterValue('proteins_max');
            if ($proteins_max !== null && $proteins_max !== '') {
                $query->andWhere('f.proteins <= :proteins_max')
                    ->setParameter('proteins_max', (float)$proteins_max);
            }

            $carbs_min = $filterService->getFilterValue('carbs_min');
            if ($carbs_min !== null && $carbs_min !== '') {
                $query->andWhere('f.carbs >= :carbs_min')
                    ->setParameter('carbs_min', (float)$carbs_min);
            }

            $carbs_max = $filterService->getFilterValue('carbs_max');
            if ($carbs_max !== null && $carbs_max !== '') {
                $query->andWhere('f.carbs <= :carbs_max')
                    ->setParameter('carbs_max', (float)$carbs_max);
            }

            $fats_min = $filterService->getFilterValue('fats_min');
            if ($fats_min !== null && $fats_min !== '') {
                $query->andWhere('f.fats >= :fats_min')
                    ->setParameter('fats_min', (float)$fats_min);
            }

            $fats_max = $filterService->getFilterValue('fats_max');
            if ($fats_max !== null && $fats_max !== '') {
                $query->andWhere('f.fats <= :fats_max')
                    ->setParameter('fats_max', (float)$fats_max);
            }

    }
}
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A FOOD ITEM
     * ES: FUNCIÓN PARA CREAR UN ALIMENTO
     *
     * @param string $description
     * @param float $calories
     * @param float $proteins
     * @param float $carbs
     * @param float $fats
     * @param string $name
     * @param Food $food
     * @param User $user
     * @return Food|null
     */
    // --------------------------------------------------------------
public function create(
    string $name,
    ?string $description,
    float $calories,
    float $proteins,
    float $carbs,
    float $fats,
    User $user
): Food|null {
    $food = (new Food())
        ->setName($name)
        ->setDescription($description)
        ->setCalories($calories)
        ->setProteins($proteins)
        ->setCarbs($carbs)
        ->setFats($fats)
        ->setUser($user);

    $this->save($this->_em, $food);

    return $food;
}

    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT A FOOD ITEM
     * ES: FUNCIÓN PARA EDITAR UN ALIMENTO
     *
     * @param Food $food
     * @param string $name
     * @param string|null $description
     * @param float|null $calories
     * @param float|null $proteins
     * @param float|null $carbs
     * @param float|null $fats
     * @return Food|null
     */
    public function edit(
        Food $food,
        string $name,
        ?string $description = null,
        ?float $calories = null,
        ?float $proteins = null,
        ?float $carbs = null,
        ?float $fats = null,
    ): Food|null
    {
        $food->setName($name);

        if ($description !== null) {
            $food->setDescription($description);
        }

        if ($calories !== null) {
            $food->setCalories($calories);
        }

        if ($proteins !== null) {
            $food->setProteins($proteins);
        }

        if ($carbs !== null) {
            $food->setCarbs($carbs);
        }

        if ($fats !== null) {
            $food->setFats($fats);
        }

        $this->save($this->_em, $food);

        return $food;
    }

    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A FOOD ITEM
     * ES: FUNCIÓN PARA ELIMINAR UN ALIMENTO
     *
     * @param Food $food
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        Food $food
    ): null
    {
        $this->delete($this->_em, $food);

        return null;
    }
    // --------------------------------------------------------------
}