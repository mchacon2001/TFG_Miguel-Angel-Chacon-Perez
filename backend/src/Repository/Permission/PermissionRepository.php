<?php

namespace App\Repository\Permission;

use App\Entity\User\Role;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class PermissionRepository extends EntityRepository
{
    // -----------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A PERMISSION BY ID
     * ES: FUNCIÓN PARA ENCONTRAR UN PERMISO POR ID
     *
     * @param int $permissionId
     * @param bool $array
     * @return Role|array|null
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------------
    public function findById(int $permissionId, bool $array = false): null|Role|array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.permissionGroup', 'permissionGroup')
            ->addSelect('permissionGroup')
            ->andWhere('p.id = :id')
            ->setParameter('id', $permissionId)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: FUNCTION TO FIND A PERMISSIONS BY IDS
     * ES: FUNCIÓN PARA ENCONTRAR PERMISOS POR IDS
     *
     * @param array $permissionIds
     * @param bool $array
     * @return Role|array|null
     */
    // -----------------------------------------------------------------
    public function findByIds(array $permissionIds, bool $array = false): null|Role|array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.permissionGroup', 'permissionGroup')
            ->addSelect('permissionGroup')
            ->andWhere('p.id IN(:ids)')
            ->setParameter('ids', $permissionIds)
            ->getQuery()
            ->getResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
    // -----------------------------------------------------------------
}