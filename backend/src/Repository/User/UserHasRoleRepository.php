<?php

namespace App\Repository\User;

use App\Entity\User\UserHasRole;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class UserHasRoleRepository extends EntityRepository
{
    /**
     * @throws NonUniqueResultException
     */
    public function getRolByUser(string $user_id, ?bool $array = false): UserHasRole|array|null
    {

        return $this->createQueryBuilder('uhr')
            ->leftJoin('uhr.role', 'r')
            ->addSelect('r')
            ->andWhere('uhr.user = :user')
            ->setParameter('user', $user_id)
            ->getQuery()
            ->getOneOrNullResult($array ? AbstractQuery::HYDRATE_ARRAY : AbstractQuery::HYDRATE_OBJECT);
    }
}