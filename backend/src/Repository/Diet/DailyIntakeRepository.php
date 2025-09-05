<?php

namespace App\Repository\Diet;

use App\Entity\Diet\DailyIntake;
use App\Entity\Diet\Diet;
use App\Entity\Food\Food;
use App\Entity\User\User;
use App\Entity\User\UserHasDiet;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DailyIntakeRepository extends EntityRepository
{
    use DoctrineStorableObject;


    public function create(
        User $user,
        Food $food,
        string $mealType,
        float $quantity
    ): DailyIntake {
        $dailyIntake = (new DailyIntake())
            ->setUser($user)
            ->setFood($food)
            ->setCreatedAt(new DateTime())
            ->setMealType($mealType)
            ->setAmount($quantity);

       $this->save($this->_em, $dailyIntake);

        return $dailyIntake;
    }


    /**
     * @ES EDITAR 
     */
    public function edit(
        DailyIntake $dailyIntake,
        User $user,
        Diet $diet
    ): DailyIntake {
        $dailyIntake->setUser($user);
        $dailyIntake->setDiet($diet);

        $this->save($this->_em, $dailyIntake);

        return $dailyIntake;
    }

    /**
     * @ES ELIMINAR
     */
    public function remove(DailyIntake $dailyIntake): void
    {
        $this->delete($this->_em, $dailyIntake);
    }


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST DAILY INTAKES
     * ES: FUNCIÓN PARA LISTAR INTAKES DIARIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('di')
            ->leftJoin('di.diet', 'd')
            ->leftJoin('di.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            //->leftJoin('d.user', 'creator')
            //->leftJoin('creator.userRoles', 'creatorUserRoles')
            //->leftJoin('creatorUserRoles.role', 'creatorRole') 
            //->leftJoin('user.userRoles', 'userRoles')
            //->leftJoin('userRoles.role', 'role')
            ->addSelect('d')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
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
            'dailyIntakes'    => $result,
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
                        $query->orderBy('di.id', $order['order']);
                        break;
                    case "amount":
                        $query->orderBy('di.amount', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('di.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('di.updatedAt', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('creator.name', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('di.createdAt', 'DESC');
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
                    $conditions[] = 'di.name LIKE :' . $param . ' OR di.description LIKE :' . $param;
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

            $user = $filterService->getFilterValue('user');
            if ($user != null) {
                $query->andWhere('user.id = :user')
                    ->setParameter('user', $user);
            }
        }
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DAILY INTAKE BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UNA INGESTA DIARIA POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleDietById(string $id, ?bool $array = false): null|UserHasDiet|array
    {
        $query = $this->createQueryBuilder('di')
            ->andWhere('di.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------



    /**
     * EN: FUNCTION TO FIND DAILY INTAKES BY USER AND DATE
     * ES: FUNCIÓN PARA ENCONTRAR INGESTAS DIARIAS POR USUARIO Y FECHA
     *
     * @param User $user
     * @param DateTime $date
     * @param bool|null $array
     * @return array
     */
    public function findByUserAndDate(User $user, DateTime $date, ?bool $array = false): array
    {
        $startOfDay = (clone $date)->setTime(0, 0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('di')
            ->leftJoin('di.food', 'food')
            ->leftJoin('di.user', 'user')
            ->addSelect('food')
            ->addSelect('user')
            ->andWhere('di.user = :user')
            ->andWhere('di.createdAt >= :startOfDay')
            ->andWhere('di.createdAt <= :endOfDay')
            ->setParameter('user', $user)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('di.createdAt', 'ASC')
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }

    /**
     * EN: FUNCTION TO DELETE DAILY INTAKES BY USER AND DATE
     * ES: FUNCIÓN PARA ELIMINAR INGESTAS DIARIAS POR USUARIO Y FECHA
     *
     * @param User $user
     * @param DateTime $date
     * @return void
     */
    public function deleteByUserAndDate(User $user, DateTime $date): void
    {
        $startOfDay = (clone $date)->setTime(0, 0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        $this->createQueryBuilder('di')
            ->delete()
            ->andWhere('di.user = :user')
            ->andWhere('di.createdAt >= :startOfDay')
            ->andWhere('di.createdAt <= :endOfDay')
            ->setParameter('user', $user)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->execute();
    }

    /**
     * EN: GET CALORIES BY DATE RANGE
     * ES: OBTENER CALORÍAS POR RANGO DE FECHAS
     */
    public function getCaloriesByDateRange($user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->createQueryBuilder('di')
            ->select('DATE(di.recordedAt) as date')
            ->addSelect('SUM(di.amount * f.calories / 100) as total_calories')
            ->addSelect('SUM(di.amount * f.proteins / 100) as total_proteins')
            ->addSelect('SUM(di.amount * f.carbs / 100) as total_carbs')
            ->addSelect('SUM(di.amount * f.fats / 100) as total_fats')
            ->leftJoin('di.food', 'f')
            ->where('di.user = :user')
            ->andWhere('di.recordedAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->groupBy('DATE(di.recordedAt)')
            ->orderBy('DATE(di.recordedAt)', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}