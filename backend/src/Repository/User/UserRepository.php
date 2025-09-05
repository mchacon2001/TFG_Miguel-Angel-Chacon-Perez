<?php

namespace App\Repository\User;

use App\Entity\Document\Document;
use App\Entity\User\Role;
use App\Entity\User\User;
use App\Entity\User\UserHasPermission;
use App\Entity\User\UserHasRole;
use App\Entity\User\UserHasPhysicalStats;
use App\Entity\User\UserHasMentalStats;
use App\Utils\Storage\DoctrineStorableObject;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends EntityRepository
{
    use DoctrineStorableObject;

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A USER BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN USUARIO POR ID
     *
     * @param string $id
     * @param bool|null $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findById(string $id, ?bool $array = false): null|User|array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.profileImg', 'profileImg')
            ->leftJoin('u.userRoles', 'userHasRole')
            ->leftJoin('userHasRole.role', 'role')
            ->leftJoin('u.userPermissions', 'userPermissions')
            ->leftJoin('userPermissions.permission', 'permission')
            ->addSelect('profileImg')
            ->addSelect('userHasRole')
            ->addSelect('userPermissions')
            ->addSelect('permission')
            ->addSelect('role')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN USER BY TEMPORAL HASH
     * ES: FUNCIÓN PARA ENCONTRAR UN USUARIO POR HASH TEMPORAL
     *
     * @param string $id
     * @param bool|null $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findUserRolePermissions(string $id, ?bool $array = false): null|User|array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.userRoles', 'userHasRole')
            ->leftJoin('userHasRole.role', 'role')
            ->leftJoin('role.permissions', 'rolePermissions')
            ->leftJoin('rolePermissions.permission', 'rolePermission')
            ->addSelect('userHasRole')
            ->addSelect('role')
            ->addSelect('rolePermissions')
            ->addSelect('rolePermission')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------



    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND AN USER BY ID (SIMPLE WAY)
     * ES: FUNCIÓN PARA ENCONTRAR UN USUARIO POR ID (FORMA SIMPLE)
     *
     * @param string $id
     * @param bool|null $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findSimpleUserById(string $id, ?bool $array = false): null|User|array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A USER BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN USUARIO POR ID
     *
     * @param string $email
     * @param bool|null $array
     * @return User|array|null
     * @throws NonUniqueResultException
     */
    // --------------------------------------------------------------
    public function findByEmail(string $email, ?bool $array = false): null|User|array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.profileImg', 'profileImg')
            ->leftJoin('u.userRoles', 'userHasRole')
            ->leftJoin('userHasRole.role', 'role')
            ->addSelect('profileImg')
            ->addSelect('userHasRole')
            ->addSelect('role')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO UPDATE THE LAST LOGIN OF A USER
     * ES: FUNCIÓN PARA ACTUALIZAR EL ÚLTIMO LOGIN DE UN USUARIO
     *
     * @param User $user
     * @return User
     */
    // --------------------------------------------------------------
    public function updateLastLogin(User $user): User
    {
        $user->setLastLogin(new DateTime());

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST USERS
     * ES: FUNCIÓN PARA LISTAR USUARIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // --------------------------------------------------------------
    public function list(FilterService $filterService): array
    {
        $query = $this->createQueryBuilder('u')
            ->leftJoin('u.profileImg', 'profileImg')
            ->leftJoin('u.userRoles', 'userHasRole')
            ->leftJoin('userHasRole.role', 'role')
            ->addSelect('profileImg')
            ->addSelect('userHasRole')
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
            unset($verification['password']);
            $result[] = $verification;
        }

        $lastPage = (integer)ceil($totalRegisters / $filterService->limit);

        return [
            'totalRegisters' => $totalRegisters,
            'users'          => $result,
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
                        $query->orderBy('u.id', $order['order']);
                        break;
                    case "name":
                        $query->orderBy('u.name', $order['order']);
                        break;
                    case "email":
                        $query->orderBy('u.email', $order['order']);
                        break;
                    case "active":
                        $query->orderBy('u.active', $order['order']);
                        break;
                    case "roles":
                        $query->orderBy('role.name', $order['order']);
                        break;
                    case "created_at":
                        $query->orderBy('u.createdAt', $order['order']);
                        break;
                    case "updated_at":
                        $query->orderBy('u.updatedAt', $order['order']);
                        break;
                    case "last_login_at":
                        $query->orderBy('u.lastLogin', $order['order']);
                        break;
                }
            }
        }
        else
        {
            $query->orderBy('u.createdAt', 'DESC');
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
                    $conditions[] = 'u.name LIKE :' . $param . ' OR u.email LIKE :' . $param;
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

            $roles = $filterService->getFilterValue('roles');
            if($roles != null)
            {
                $where = 'role.id LIKE ';
                $rolesAux = [];
                foreach ($roles as $index => $role)
                {
                    if($index == 0)
                    {
                        $where = $where.':role'.$index;
                    }
                    else
                    {
                        $where = $where.' OR role.id LIKE :role'.$index;
                    }
                    $rolesAux[] = $role;
                }

                $query->orWhere($where);
                foreach ($rolesAux as $index => $role)
                {
                    $query->setParameter('role'.$index, "%".$role."%");
                }
            }

            $active = $filterService->getFilterValue('active');
            if($active === 1 || $active === 0)
            {
                $query->andWhere('u.active = :active')
                    ->setParameter('active', $active);
            }

            $betweenDates = $filterService->getFilterValue('between_dates');
            if($betweenDates != null)
            {
                $from = DateTime::createFromFormat('Y-m-d', $betweenDates['startDate'])->setTime(0,0,0);
                $to   = DateTime::createFromFormat('Y-m-d', $betweenDates['endDate'])->setTime(23, 59, 59);

                $query->andWhere('u.createdAt BETWEEN :from AND :to')
                    ->setParameter('from', $from)
                    ->setParameter('to', $to);
            }

            $toGainMuscle = $filterService->getFilterValue('toGainMuscle');
        if ($toGainMuscle !== null) {
            $query->andWhere('u.toGainMuscle = :toGainMuscle')
                ->setParameter('toGainMuscle', (bool)$toGainMuscle);
        }

        $toLoseWeight = $filterService->getFilterValue('toLoseWeight');
        if ($toLoseWeight !== null) {
            $query->andWhere('u.toLoseWeight = :toLoseWeight')
                ->setParameter('toLoseWeight', (bool)$toLoseWeight);
        }

        $toMaintainWeight = $filterService->getFilterValue('toMaintainWeight');
        if ($toMaintainWeight !== null) {
            $query->andWhere('u.toMaintainWeight = :toMaintainWeight')
                ->setParameter('toMaintainWeight', (bool)$toMaintainWeight);
        }

        $toImprovePhysicalHealth = $filterService->getFilterValue('toImprovePhysicalHealth');
        if ($toImprovePhysicalHealth !== null) {
            $query->andWhere('u.toImprovePhysicalHealth = :toImprovePhysicalHealth')
                ->setParameter('toImprovePhysicalHealth', (bool)$toImprovePhysicalHealth);
        }

        $toImproveMentalHealth = $filterService->getFilterValue('toImproveMentalHealth');
        if ($toImproveMentalHealth !== null) {
            $query->andWhere('u.toImproveMentalHealth = :toImproveMentalHealth')
                ->setParameter('toImproveMentalHealth', (bool)$toImproveMentalHealth);
        }

        $fixShoulder = $filterService->getFilterValue('fixShoulder');
        if ($fixShoulder !== null) {
            $query->andWhere('u.fixShoulder = :fixShoulder')
                ->setParameter('fixShoulder', (bool)$fixShoulder);
        }

        $fixKnees = $filterService->getFilterValue('fixKnees');
        if ($fixKnees !== null) {
            $query->andWhere('u.fixKnees = :fixKnees')
                ->setParameter('fixKnees', (bool)$fixKnees);
        }

        $fixBack = $filterService->getFilterValue('fixBack');
        if ($fixBack !== null) {
            $query->andWhere('u.fixBack = :fixBack')
                ->setParameter('fixBack', (bool)$fixBack);
        }

        $rehab = $filterService->getFilterValue('rehab');
        if ($rehab !== null) {
            $query->andWhere('u.rehab = :rehab')
                ->setParameter('rehab', (bool)$rehab);
        }
        }
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE A BASIC USER
     * ES: FUNCIÓN PARA CREAR UN USUARIO BÁSICO
     *
     * @param UserPasswordHasherInterface $encoder
     * @param string $email
     * @param string $pass
     * @param string $name
     * @param string $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @return User|null
     */
    // --------------------------------------------------------------
    public function createBasicUser(UserPasswordHasherInterface $encoder, string $email, string $pass, string $name, string $targetWeight, string $sex, DateTime $birthdate): ?User
    {
        $user = (new User())
            ->setEmail($email)
            ->setPassword($pass)
            ->setName($name)
            ->setTargetWeight($targetWeight)
            ->setSex($sex)
            ->setBirthdate($birthdate)
        ;

        $user->setPassword($encoder->hashPassword($user, $pass));

        $this->save($this->_em, $user);
        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CREATE USER
     * ES: FUNCIÓN PARA CREAR USUARIO
     *
     * @param UserPasswordHasherInterface $encoder
     * @param string $email
     * @param string $pass
     * @param string $name
     * @param Role|null $role
     * @param array $permissions
     * @param string $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @param array $permissions
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return User|null
     */
    // --------------------------------------------------------------
    public function create(
        UserPasswordHasherInterface $encoder,
        string $email,
        string $pass,
        string $name,
        string $targetWeight,
        string $sex,
        DateTime $birthdate,
        ?Role $role,
        array $permissions,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): ?User
    {
        $user = (new User())
            ->setName($name)
            ->setEmail($email)
            ->setTargetWeight($targetWeight)
            ->setSex($sex)
            ->setBirthdate($birthdate)
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

        $user->setPassword($encoder->hashPassword($user, $pass));

        if($role)
        {
            $this->addRoleToUser($user, $role);
        }

        $this->addPermissionsToUser($user, $permissions);

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO INSERT AN IMAGE ON USER PROFILE
     * ES: FUNCIÓN PARA INSERTAR UNA IMAGEN EN EL PERFIL DE USUARIO
     *
     * @param User $user
     * @param Document|null $image
     * @return User|null
     */
    // ----------------------------------------------------------------
    public function insertImage(
        User $user,
        ?Document $image): ?User
    {
        $user->setProfileImg($image);

        $this->save($this->_em, $user);

        return $user;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE AN IMAGE ON USER PROFILE
     * ES: FUNCIÓN PARA ELIMINAR UNA IMAGEN EN EL PERFIL DE USUARIO
     *
     * @param User $user
     * @return User|null
     */
    // ----------------------------------------------------------------
    public function removeImage(User $user): ?User
    {
        $user->setProfileImg(null);

        $this->save($this->_em, $user);

        return $user;
    }
    // ----------------------------------------------------------------


    // ----------------------------------------------------------------
    /**
     * EN: FUNCTION TO EDIT USER
     * ES: FUNCIÓN PARA EDITAR USUARIO
     *
     * @param User $user
     * @param string $email
     * @param string $name
     * @param Role|null $role
     * @param array $permissions
     * @param UserHasRole $userHasRole
     * @param string $targetWeight
     * @param string $sex
     * @param DateTime $birthdate
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return User|null
     */
    // ----------------------------------------------------------------
    public function edit(
        User $user,
        string $email,
        string $name,
        string $targetWeight,
        string $sex,
        DateTime $birthdate,
        ?Role $role,
        ?array $permissions,
        UserHasRole $userHasRole,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): ?User
    {
        $user
            ->setEmail($email)
            ->setName($name)
            ->setTargetWeight($targetWeight)
            ->setSex($sex)
            ->setBirthdate($birthdate)
            ->setUpdatedAt(new DateTime('now'))
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

        if($role)
        {
            $this->removeRoleOfUser($user, $userHasRole);
            $this->addRoleToUser($user, $role);
        }

        if($permissions)
        {
            $this->removePermissions($user);
            $this->addPermissionsToUser($user, $permissions);
        }

        $this->save($this->_em, $user);

        return $user;
    }
    // ----------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO CHANGE USER PASSWORD
     * ES: FUNCIÓN PARA CAMBIAR LA CONTRASEÑA DEL USUARIO
     *
     * @param UserPasswordHasherInterface $encoder
     * @param User $user
     * @param string $pass
     * @return User
     */
    // --------------------------------------------------------------
    public function changePassword(
        UserPasswordHasherInterface $encoder,
        User $user,
        string $pass
    ): User
    {

        $user
            ->setPassword($encoder->hashPassword($user, $pass))
            ->setUpdatedAt(new DateTime('now'));

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE AN USER
     * ES: FUNCIÓN PARA ELIMINAR UN USUARIO
     *
     * @param User $user
     * @return null
     */
    // --------------------------------------------------------------
    public function remove(
        User $user
    ): null
    {
        $this->delete($this->_em, $user);

        return null;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO ADD A ROLE TO USER
     * ES: FUNCIÓN PARA AGREGAR UN ROL A UN USUARIO
     *
     * @param User $user
     * @param Role $role
     * @return User
     */
    // --------------------------------------------------------------
    public function addRoleToUser(User $user, Role $role): User
    {
        $newRole = (new UserHasRole())
            ->setRole($role)
            ->setUser($user)
        ;

        $user
            ->addUserRole($newRole)
            ->setUpdatedAt(new DateTime('now'));

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE A ROLE OF USER
     * ES: FUNCIÓN PARA ELIMINAR UN ROL DE UN USUARIO
     *
     * @param User $user
     * @param UserHasRole $userHasRole
     * @return User
     */
    // --------------------------------------------------------------
    public function removeRoleOfUser(User $user, UserHasRole $userHasRole): User
    {
        $user
            ->removeUserRole($userHasRole)
            ->setUpdatedAt(new DateTime('now'));

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO ADD PERMISSIONS TO USER
     * ES: FUNCIÓN PARA AGREGAR PERMISOS A UN USUARIO
     *
     * @param User $user
     * @param array $permissions
     * @return User|array|null
     */
    // --------------------------------------------------------------
    public function addPermissionsToUser(User $user, array $permissions): User|array|null
    {

        foreach ($permissions as $permission)
        {
            $userHasPermission = (new UserHasPermission())
            ->setUser($user)
            ->setPermission($permission);

            $user
                ->addPermission($userHasPermission)
                ->setUpdatedAt(new DateTime('now'));
        }
        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE PERMISSIONS OF USER
     * ES: FUNCIÓN PARA ELIMINAR PERMISOS DE UN USUARIO
     *
     * @param User $user
     * @return User
     */
    // --------------------------------------------------------------
    public function removePermissions(
        User &$user): User
    {
        foreach ($user->getUserPermissions() as $permission)
        {
            $user
                ->removePermission($permission)
                ->setUpdatedAt(new DateTime('now'));
        }

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------

        // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO TOGGLE USER STATUS
     * ES: FUNCIÓN PARA CAMBIAR EL ESTADO DE UN USUARIO
     *
     * @param User $user
     * @return User|string|null
     */
    // --------------------------------------------------------------
    public function toggleUser(User $user): User|string|null
    {
        $user
            ->setActive(!$user->isActive())
            ->setUpdatedAt(new DateTime('now'));

        $status = 'activado';

        if(!$user->isActive())
        {
            $status = 'desactivado';
        }

        $this->save($this->_em, $user);

        return $status;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO ASSIGN THE TEMPORAL HASH TO USER
     * ES: FUNCIÓN PARA ASIGNAR EL HASH TEMPORAL AL USUARIO
     *
     * @param User $user
     * @param string $hash
     * @return User
     */
    // --------------------------------------------------------------
    public function assignHash(
        User $user,
        string $hash): User
    {
        $user->setTemporalHash($hash);

        $this->save($this->_em, $user);

        return $user;
    }
    // --------------------------------------------------------------


    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO REMOVE THE TEMPORAL HASH OF USER
     * ES: FUNCIÓN PARA ELIMINAR EL HASH TEMPORAL DEL USUARIO
     *
     * @param User $user
     * @return User
     */
    // --------------------------------------------------------------
    public function removeHash(User $user): User
    {
        $user->setTemporalHash(null);

        return $user;
    }
    // --------------------------------------------------------------

    /**
     * EN: FUNCTION TO ADD PHYSICAL STATS FOR A USER
     * ES: FUNCIÓN PARA AGREGAR ESTADÍSTICAS FÍSICAS PARA UN USUARIO
     *
     * @param User $user
     * @param float|null $height
     * @param float|null $weight
     * @param float|null $bodyFat
     * @param float|null $bmi
     */
    public function addPhysicalStats(
        User $user,
        ?float $height,
        ?float $weight,
        ?float $bodyFat,
        ?float $bmi
    ) {
        // Solo crear stats si height y weight no son null
        if ($height === null || $weight === null) {
            return null;
        }

        $physicalStats = new UserHasPhysicalStats();
        $physicalStats->setUser($user)
            ->setHeight($height)
            ->setWeight($weight)
            ->setBodyFat($bodyFat)
            ->setBmi($bmi)
            ->setRecordedAt(new DateTime());

        $this->_em->persist($physicalStats);
        $this->_em->flush();

        return $physicalStats;
    }

    /**
     * EN: FUNCTION TO ADD MENTAL STATS FOR A USER
     * ES: FUNCIÓN PARA AGREGAR ESTADÍSTICAS MENTALES PARA UN USUARIO
     *
     * @param User $user
     * @param float $mood
     * @param float $sleepQuality
     * @return UserHasMentalStats
     */
    // --------------------------------------------------------------
    public function addMentalStats(
        User $user,
        float $mood,
        float $sleepQuality
    ) {
        $mentalStats = new UserHasMentalStats();
        $mentalStats->setUser($user)
            ->setMood($mood)
            ->setSleepQuality($sleepQuality)
            ->setRecordedAt(new DateTime());

        $this->_em->persist($mentalStats);
        $this->_em->flush();

        return $mentalStats;
    }

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL PHYSICAL STATS OF A USER
     * ES: FUNCIÓN PARA OBTENER TODAS LAS ESTADÍSTICAS FÍSICAS DE UN USUARIO
     *
     * @param User $user
     * @return array
     */
    // --------------------------------------------------------------
    public function getPhysicalStats(User $user): array
    {
        $stats = $this->_em->getRepository(UserHasPhysicalStats::class)
            ->findBy(['user' => $user], ['recordedAt' => 'ASC']);

        return array_map(function($stat) {
            return [
                'weight' => $stat->getWeight(),
                'height' => $stat->getHeight(),
                'bodyFat' => $stat->getBodyFat(),
                'bmi' => $stat->getBmi(),
                'recordedAt' => $stat->getRecordedAt(),
            ];
        }, $stats);
    }
    // --------------------------------------------------------------

    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET ALL MENTAL STATS OF A USER
     * ES: FUNCIÓN PARA OBTENER TODAS LAS ESTADÍSTICAS MENTALES DE UN USUARIO
     *
     * @param User $user
     * @return array
     */
    // --------------------------------------------------------------
    public function getMentalStats(User $user): array
    {
        $stats = $this->_em->getRepository(UserHasMentalStats::class)
            ->findBy(['user' => $user], ['recordedAt' => 'ASC']);

        return array_map(function($stat) {
            return [
                'mood' => $stat->getMood(),
                'sleepQuality' => $stat->getSleepQuality(),
                'recordedAt' => $stat->getRecordedAt(),
            ];
        }, $stats);
    }
    // --------------------------------------------------------------

    /**
     * EN: FUNCTION TO FIND USERS BY FLAGS
     * ES: FUNCIÓN PARA ENCONTRAR USUARIOS POR FLAGS
     *
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return array
     */
    public function findUsersByFlags(
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): array
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.userRoles', 'userRoles')
            ->leftJoin('userRoles.role', 'role')
            ->where('u.active = :active')
            ->andWhere('role.id NOT IN (:excludedRoles)')
            ->setParameter('active', true)
            ->setParameter('excludedRoles', [1, 2]); // Assuming 1 = SUPER_ADMIN, 2 = ADMIN

        $orConditions = [];
        $parameters = [];

        if ($toGainMuscle) {
            $orConditions[] = 'u.toGainMuscle = :toGainMuscle';
            $parameters['toGainMuscle'] = true;
        }

        if ($toLoseWeight) {
            $orConditions[] = 'u.toLoseWeight = :toLoseWeight';
            $parameters['toLoseWeight'] = true;
        }

        if ($toMaintainWeight) {
            $orConditions[] = 'u.toMaintainWeight = :toMaintainWeight';
            $parameters['toMaintainWeight'] = true;
        }

        if ($toImprovePhysicalHealth) {
            $orConditions[] = 'u.toImprovePhysicalHealth = :toImprovePhysicalHealth';
            $parameters['toImprovePhysicalHealth'] = true;
        }

        if ($toImproveMentalHealth) {
            $orConditions[] = 'u.toImproveMentalHealth = :toImproveMentalHealth';
            $parameters['toImproveMentalHealth'] = true;
        }

        if ($fixShoulder) {
            $orConditions[] = 'u.fixShoulder = :fixShoulder';
            $parameters['fixShoulder'] = true;
        }

        if ($fixKnees) {
            $orConditions[] = 'u.fixKnees = :fixKnees';
            $parameters['fixKnees'] = true;
        }

        if ($fixBack) {
            $orConditions[] = 'u.fixBack = :fixBack';
            $parameters['fixBack'] = true;
        }

        if ($rehab) {
            $orConditions[] = 'u.rehab = :rehab';
            $parameters['rehab'] = true;
        }

        // Only add conditions if there are flags to match
        if (!empty($orConditions)) {
            $qb->andWhere('(' . implode(' OR ', $orConditions) . ')');
            foreach ($parameters as $key => $value) {
                $qb->setParameter($key, $value);
            }
        } else {
            // If no flags are set, return empty array
            return [];
        }

        return $qb->getQuery()->getResult();
    }
    // --------------------------------------------------------------

    /**
     * EN: HELPER FUNCTION TO GET CURRENT DAY OF WEEK IN SPANISH
     * ES: FUNCIÓN AUXILIAR PARA OBTENER EL DÍA ACTUAL DE LA SEMANA EN ESPAÑOL
     *
     * @return string
     */
    private function getCurrentDayOfWeek(): string
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes', 
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        
        return $days[(int) date('N')];
    }
    // --------------------------------------------------------------
    /**
     * EN: FUNCTION TO GET PHYSICAL STATS FOR PERIOD
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS FÍSICAS PARA PERÍODO
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getPhysicalStatsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        $stats = $this->_em->getRepository(UserHasPhysicalStats::class)
            ->createQueryBuilder('ups')
            ->where('ups.user = :user')
            ->andWhere('ups.recordedAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('ups.recordedAt', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function($stat) {
            return [
                'weight' => $stat->getWeight(),
                'height' => $stat->getHeight(),
                'bodyFat' => $stat->getBodyFat(),
                'bmi' => $stat->getBmi(),
                'recordedAt' => $stat->getRecordedAt(),
            ];
        }, $stats);
    }

    /**
     * EN: FUNCTION TO GET MENTAL STATS FOR PERIOD
     * ES: FUNCIÓN PARA OBTENER ESTADÍSTICAS MENTALES PARA PERÍODO
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getMentalStatsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        $stats = $this->_em->getRepository(UserHasMentalStats::class)
            ->createQueryBuilder('ums')
            ->where('ums.user = :user')
            ->andWhere('ums.recordedAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('ums.recordedAt', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(function($stat) {
            return [
                'mood' => $stat->getMood(),
                'sleepQuality' => $stat->getSleepQuality(),
                'recordedAt' => $stat->getRecordedAt(),
            ];
        }, $stats);
    }

    /**
     * EN: FUNCTION TO GET EXERCISE DAYS FOR PERIOD
     * ES: FUNCIÓN PARA OBTENER DÍAS DE EJERCICIO PARA PERÍODO
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getExerciseDaysForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->_em->createQueryBuilder()
            ->select('rr.startTime as date')
            ->addSelect('r.name as routine_name')
            ->addSelect('rr.endTime')
            ->from('App\Entity\Routine\RoutineRegister', 'rr')
            ->leftJoin('rr.routines', 'r')
            ->where('rr.user = :user')
            ->andWhere('rr.startTime BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('rr.startTime', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * EN: FUNCTION TO GET EXERCISE DETAILS FOR PERIOD
     * ES: FUNCIÓN PARA OBTENER DETALLES DE EJERCICIOS PARA PERÍODO
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getExerciseDetailsForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        return $this->_em->createQueryBuilder()
            ->select('rre.createdAt as date')
            ->addSelect('e.name as exercise_name')
            ->addSelect('rre.sets')
            ->addSelect('rre.reps')
            ->addSelect('rre.weight')
            ->from('App\Entity\Routine\RoutineRegisterExercises', 'rre')
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

    /**
     * EN: FUNCTION TO GET CALORIE INTAKE FOR PERIOD
     * ES: FUNCIÓN PARA OBTENER INGESTA DE CALORÍAS PARA PERÍODO
     *
     * @param User $user
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array
     */
    public function getCalorieIntakeForPeriod(User $user, DateTime $startDate, DateTime $endDate): array
    {
        $results = $this->_em->createQueryBuilder()
            ->select('di.createdAt as date')
            ->addSelect('di.amount')
            ->addSelect('f.calories')
            ->addSelect('f.proteins')
            ->addSelect('f.carbs')
            ->addSelect('f.fats')
            ->from('App\Entity\Diet\DailyIntake', 'di')
            ->leftJoin('di.food', 'f')
            ->where('di.user = :user')
            ->andWhere('di.createdAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('di.createdAt', 'ASC')
            ->getQuery()
            ->getArrayResult();

        // Group results by date and calculate totals
        $groupedResults = [];
        foreach ($results as $result) {
            $date = $result['date']->format('Y-m-d');
            
            if (!isset($groupedResults[$date])) {
                $groupedResults[$date] = [
                    'date' => $date,
                    'total_calories' => 0,
                    'total_proteins' => 0,
                    'total_carbs' => 0,
                    'total_fats' => 0
                ];
            }
            
            $amount = $result['amount'];
            $groupedResults[$date]['total_calories'] += ($amount * $result['calories']) / 100;
            $groupedResults[$date]['total_proteins'] += ($amount * $result['proteins']) / 100;
            $groupedResults[$date]['total_carbs'] += ($amount * $result['carbs']) / 100;
            $groupedResults[$date]['total_fats'] += ($amount * $result['fats']) / 100;
        }

        return array_values($groupedResults);
    }

    public function getCalorieIntake(User $user): array
    {
        return $this->_em->createQueryBuilder()
            ->select('di.createdAt as date')
            ->addSelect('di.amount')
            ->addSelect('f.calories')
            ->addSelect('f.proteins')
            ->addSelect('f.carbs')
            ->addSelect('f.fats')
            ->from('App\Entity\Diet\DailyIntake', 'di')
            ->leftJoin('di.food', 'f')
            ->where('di.user = :user')
            ->setParameter('user', $user)
            ->orderBy('di.createdAt', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
    // --------------------------------------------------------------
}