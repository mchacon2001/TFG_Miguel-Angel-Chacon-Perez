<?php

namespace App\Repository\User;

use App\Entity\Diet\Diet;
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

class UserHasDietRepository extends EntityRepository
{
    use DoctrineStorableObject;


    public function create(
        User $user,
        Diet $diet,
    ): UserHasDiet {
        $userHasDiet = new UserHasDiet();
        $userHasDiet->setUser($user);
        $userHasDiet->setDiet($diet);

       $this->save($this->_em, $userHasDiet);

        return $userHasDiet;
    }


    /**
     * @ES EDITAR 
     */
    public function edit(
        UserHasDiet $userHasDiet,
        User $user,
        Diet $diet
    ): UserHasDiet {
        $userHasDiet->setUser($user);
        $userHasDiet->setDiet($diet);

        $this->save($this->_em, $userHasDiet);

        return $userHasDiet;
    }

    /**
     * @ES ELIMINAR
     */
    public function remove(UserHasDiet $userHasDiet): void
    {
        $this->delete($this->_em, $userHasDiet);
    }


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
        $query = $this->createQueryBuilder('uhd')
            ->leftJoin('uhd.diet', 'd')
            ->leftJoin('uhd.user', 'user')
            ->leftJoin('d.user', 'creator')
            ->leftJoin('creator.userRoles', 'creatorUserRoles') // <-- Añade esto
            ->leftJoin('creatorUserRoles.role', 'creatorRole') 
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->leftJoin('user.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->addSelect('d')
            ->addSelect('user')
            ->addSelect('creator')
            ->addSelect('creatorUserRoles')
            ->addSelect('creatorRole')
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
                    case "active":
                        $query->orderBy('d.active', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('d.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('d.updatedAt', $order['order']);
                        break;
                    case "user":
                        $query->orderBy('creator.name', $order['order']);
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
                    $conditions[] = 'uhd.name LIKE :' . $param . ' OR uhd.description LIKE :' . $param;
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


            $foods = $filterService->getFilterValue('foods');
            if($foods != null)
            {
                $query->andWhere('food.id IN (:foods)')
                    ->setParameter('foods', $foods);
            }

            $user = $filterService->getFilterValue('user');
            if ($user != null) {
                $query->andWhere('user.id = :user')
                    ->setParameter('user', $user);
            }
            $selectedDiet = $filterService->getFilterValue('selectedDiet');
            if ($selectedDiet !== null) {
                $query->andWhere('uhd.selectedDiet = :selectedDiet')
                    ->setParameter('selectedDiet', $selectedDiet);
            }
           
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A DIET BY NAME
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return UserHasDiet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByName(string $name, ?bool $array = false): null|UserHasDiet|array
    {
        return $this->createQueryBuilder('uhd')
            ->leftJoin('uhd.diet', 'd')
            ->leftJoin('uhd.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('d')
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
     * EN: FUNCTION TO FIND A DIET BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleDietById(string $id, ?bool $array = false): null|UserHasDiet|array
    {
        $query = $this->createQueryBuilder('uhd')
            ->andWhere('uhd.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------



    /**
     * EN: FUNCTION TO FIND A DIET BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return UserHasDiet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|UserHasDiet|array
    {
        return $this->createQueryBuilder('uhd')
            ->leftJoin('uhd.diet', 'd')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('d')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->andWhere('uhd.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO TOGGLE A USER HAS DIET
     * ES: FUNCIÓN PARA ACTIVAR O DESACTIVAR UN USER HAS DIET
     *
     * @param UserHasDiet $userHasDiet
     * @return UserHasDiet|string|null
     */
    // ----------------------------------------------------------------
    public function toggleUserHasDiet(UserHasDiet $userHasDiet): UserHasDiet|null|string
    {
        $userHasDiet->setSelectedDiet(!$userHasDiet->isSelectedDiet());

        if($userHasDiet->isSelectedDiet() === true)
        {
            $message = 'Dieta seleccionada';
        }
        else
        {
            $message = 'Dieta desactivada';
        }

        $this->save($this->_em, $userHasDiet);

        return $message;
    }
    // ----------------------------------------------------------------

public function deactivateAllUserDiets(string $userId): void
{
    // Obtener la entidad User
    $user = $this->_em->getRepository(User::class)->find($userId);

    // Obtener todas las dietas activas del usuario
    $userDiets = $this->createQueryBuilder('uhd')
        ->where('uhd.user = :user')
        ->andWhere('uhd.selectedDiet = true')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();

    // Desactivar cada dieta activa
    foreach ($userDiets as $userDiet) {
        $userDiet->setSelectedDiet(false);
        $this->_em->persist($userDiet);
    }

    $this->_em->flush();
}

    /**
     * EN: FUNCTION TO FIND A DIET BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UNA DIETA POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return UserHasDiet|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findUserHasDietByUserAndSelectedDiet(string $userId, bool $selectedDiet): null|UserHasDiet|array
    {
        return $this->createQueryBuilder('uhd')
            ->leftJoin('uhd.diet', 'd')
            ->leftJoin('d.user', 'user')
            ->leftJoin('d.dietHasFood', 'dietHasFood')
            ->leftJoin('dietHasFood.food', 'food')
            ->addSelect('d')
            ->addSelect('user')
            ->addSelect('dietHasFood')
            ->addSelect('food')
            ->andWhere('uhd.user = :userId')
            ->andWhere('uhd.selectedDiet = true')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT); // Forzar siempre un objeto
    }
    // --------------------------------------------------------------

    /**
     * EN: FUNCTION TO DELETE ALL USER-DIET RELATIONS BY DIET ID
     * ES: FUNCIÓN PARA ELIMINAR TODAS LAS RELACIONES USUARIO-DIETA POR ID DE DIETA
     *
     * @param string $dietId
     * @return void
     */
    public function deleteByDietId(string $dietId): void
    {
        $this->createQueryBuilder('uhd')
            ->delete()
            ->where('uhd.diet = :dietId')
            ->setParameter('dietId', $dietId)
            ->getQuery()
            ->execute();
    }

    /**
     * EN: FUNCTION TO FIND USER-DIET RELATION BY USER AND DIET
     * ES: FUNCIÓN PARA ENCONTRAR RELACIÓN USUARIO-DIETA POR USUARIO Y DIETA
     *
     * @param string $userId
     * @param string $dietId
     * @return UserHasDiet|null
     */
    public function findByUserAndDiet(string $userId, string $dietId): ?UserHasDiet
    {
        return $this->createQueryBuilder('uhd')
            ->andWhere('uhd.user = :userId')
            ->andWhere('uhd.diet = :dietId')
            ->setParameter('userId', $userId)
            ->setParameter('dietId', $dietId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    // --------------------------------------------------------------
}