<?php


namespace App\Repository\Permission;


use App\Entity\Permission\PermissionGroup;
use App\Utils\Storage\DoctrineStorableObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class PermissionGroupRepository extends EntityRepository
{
    use DoctrineStorableObject;

    public function getAvailablePermission(bool $array = false)
    {
        return $this->_em->getRepository(PermissionGroup::class)
            ->createQueryBuilder('gp')
            ->select('gp')
            ->leftJoin('gp.permissions', 'permissions')
            ->addSelect('permissions')
            ->getQuery()
            ->getResult($array ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
    }

    public function getAvailablePermissionForNonSuperAdmin(bool $array = false)
    {
        return $this->_em->getRepository(PermissionGroup::class)
            ->createQueryBuilder('gp')
            ->select('gp')
            ->leftJoin('gp.permissions', 'permissions')
            ->addSelect('permissions')
            ->andWhere('permissions.adminManaged = 0')
            ->getQuery()
            ->getResult($array ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT);
    }

}