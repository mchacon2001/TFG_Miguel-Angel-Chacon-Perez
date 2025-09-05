<?php
namespace App\Repository\Diet;

use App\Entity\Diet\Diet;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use App\Entity\User\User;
use App\Utils\Storage\DoctrineStorableObject;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
class DietRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DIET BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------

    public function findById(string $id, $array): Diet|array|null
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->leftJoin('d.userDiets', 'userDiets')
            ->leftJoin('userDiets.user', 'userDietsUser')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->addSelect('userDiets')
            ->addSelect('userDietsUser')
            ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    /**
     * EN: FUNCTION TO FIND A DIET BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleDietById(string $id, ?bool $array = false): null|Diet|array
    {
        $query = $this->createQueryBuilder('d')
            ->andWhere('d.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DIET BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findDietByName(string $name, ?bool $array = false): null|Diet|array
    {
        $query = $this->createQueryBuilder('d')
            ->andWhere('d.name = :name')
            ->setParameter('name', $name);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL DIETS
     * ES: FUNCIÓN PARA OBTENER TODAS LAS DIETAS
     *
     * @param bool|null $array
     * @return array|Diet|null
     */
    // --------------------------------------------------------------
    public function getAllDiets(?bool $array = false): array|Diet|null
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

        // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET DIET FOOD
     * ES: FUNCIÓN PARA OBTENER LOS ALIMENTOS DE UNA DIETA
     *
     * @param string $id
     * @param bool|null $array
     * @return array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function getDietFood(string $id, ?bool $array = false): Diet|null|array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->andWhere('d.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DIET BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|Diet|array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->andWhere('d.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST DIETS
     * ES: FUNCIÓN PARA LISTAR DIETAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('d')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->leftJoin('user.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->leftJoin('d.userDiets', 'userDiets')
            ->leftJoin('userDiets.user', 'userDietsUser')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->addSelect('userDiets')
            ->addSelect('userDietsUser')
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
            'diets'    => $result,
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
                        $query->orderBy('d.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('d.name', $order['order']);
                        break;
                    case "description":
                        $query->orderBy('d.description', $order['order']);
                        break;
                    case "goal":
                        $query->orderBy('d.goal', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('d.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('d.updatedAt', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('d.createdAt', 'DESC');
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
                    $conditions[] = 'd.name LIKE :' . $param . ' OR d.description LIKE :' . $param;
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

            $diets = $filterService->getFilterValue('diets');
            if($diets != null)
            {
                $query->andWhere('diet.id IN (:diets)')
                    ->setParameter('diets', $diets);
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
                    $orConditions[] = 'd.toGainMuscle = :toGainMuscle';
                    $parameters['toGainMuscle'] = (bool)$toGainMuscle;
                }

                $toLoseWeight = $filterService->getFilterValue('toLoseWeight');
                if ($toLoseWeight !== null) {
                    $orConditions[] = 'd.toLoseWeight = :toLoseWeight';
                    $parameters['toLoseWeight'] = (bool)$toLoseWeight;
                }

                $toMaintainWeight = $filterService->getFilterValue('toMaintainWeight');
                if ($toMaintainWeight !== null) {
                    $orConditions[] = 'd.toMaintainWeight = :toMaintainWeight';
                    $parameters['toMaintainWeight'] = (bool)$toMaintainWeight;
                }

                $toImprovePhysicalHealth = $filterService->getFilterValue('toImprovePhysicalHealth');
                if ($toImprovePhysicalHealth !== null) {
                    $orConditions[] = 'd.toImprovePhysicalHealth = :toImprovePhysicalHealth';
                    $parameters['toImprovePhysicalHealth'] = (bool)$toImprovePhysicalHealth;
                }

                $toImproveMentalHealth = $filterService->getFilterValue('toImproveMentalHealth');
                if ($toImproveMentalHealth !== null) {
                    $orConditions[] = 'd.toImproveMentalHealth = :toImproveMentalHealth';
                    $parameters['toImproveMentalHealth'] = (bool)$toImproveMentalHealth;
                }

                $fixShoulder = $filterService->getFilterValue('fixShoulder');
                if ($fixShoulder !== null) {
                    $orConditions[] = 'd.fixShoulder = :fixShoulder';
                    $parameters['fixShoulder'] = (bool)$fixShoulder;
                }

                $fixKnees = $filterService->getFilterValue('fixKnees');
                if ($fixKnees !== null) {
                    $orConditions[] = 'd.fixKnees = :fixKnees';
                    $parameters['fixKnees'] = (bool)$fixKnees;
                }

                $fixBack = $filterService->getFilterValue('fixBack');
                if ($fixBack !== null) {
                    $orConditions[] = 'd.fixBack = :fixBack';
                    $parameters['fixBack'] = (bool)$fixBack;
                }

                $rehab = $filterService->getFilterValue('rehab');
                if ($rehab !== null) {
                    $orConditions[] = 'd.rehab = :rehab';
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
     * EN: FUNCTION TO CREATE A DIET
     * ES: FUNCIÓN PARA CREAR UNA DIETA
     *
     * @param string $name
     * @param string $description
     * @param User|null $user
     * @param string $goal
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Diet|null
     */
    // --------------------------------------------------------------
public function create(
    string $name,
    string $description,
    ?User $user,
    string $goal,
    bool $toGainMuscle,
    bool $toLoseWeight,
    bool $toMaintainWeight,
    bool $toImprovePhysicalHealth,
    bool $toImproveMentalHealth,
    bool $fixShoulder,
    bool $fixKnees,
    bool $fixBack,
    bool $rehab
): Diet|null
{
    $diet = new Diet();

    $diet
        ->setName($name)
        ->setDescription($description)
        ->setUser($user)
        ->setCreatedAt(new DateTime('now'))
        ->setUpdatedAt(new DateTime('now'))
        ->setGoal($goal)
        ->setToGainMuscle($toGainMuscle)
        ->setToLoseWeight($toLoseWeight)
        ->setToMaintainWeight($toMaintainWeight)
        ->setToImprovePhysicalHealth($toImprovePhysicalHealth)
        ->setToImproveMentalHealth($toImproveMentalHealth)
        ->setFixShoulder($fixShoulder)
        ->setFixKnees($fixKnees)
        ->setFixBack($fixBack)
        ->setRehab($rehab);

    $this->save($this->_em, $diet);

    return $diet;
}

    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE A DIET
     * ES: FUNCIÓN PARA ACTUALIZAR UNA DIETA
     *
     * @param Diet $diet
     * @param string $name
     * @param string|null $description
     * @param string $goal
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Diet|null
     */
    // --------------------------------------------------------------
    public function edit(
        Diet $diet,
        string $name,
        ?string $description,
        string $goal,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Diet|null
    {
        $diet
            ->setName($name)
            ->setDescription($description)
            ->setGoal($goal)
            ->setUpdatedAt(new DateTime('now'))
            ->setToGainMuscle($toGainMuscle)
            ->setToLoseWeight($toLoseWeight)
            ->setToMaintainWeight($toMaintainWeight)
            ->setToImprovePhysicalHealth($toImprovePhysicalHealth)
            ->setToImproveMentalHealth($toImproveMentalHealth)
            ->setFixShoulder($fixShoulder)
            ->setFixKnees($fixKnees)
            ->setFixBack($fixBack)
            ->setRehab($rehab);

        $this->save($this->_em, $diet);

        return $diet;
    }


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO DELETE A DIET
     * ES: FUNCIÓN PARA ELIMINAR UNA DIETA
     *
     * @param Diet $diet
     * @return Diet|null
     */
    // --------------------------------------------------------------
    public function remove(Diet $diet): Diet|null
    {
        $this->delete($this->_em, $diet);

        return $diet;
    }
    // --------------------------------------------------------------
}