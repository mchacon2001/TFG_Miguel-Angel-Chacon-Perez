<?php

namespace App\Services\Document;

use App\Entity\User\Role;
use App\Repository\User\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DocumentTypeService
{
    protected RoleRepository|EntityRepository $roleRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->roleRepository = $em->getRepository(Role::class);
    }

    public function getRoleById(int $roleId): ?Role
    {
        return $this->roleRepository->findById($roleId);
    }
}