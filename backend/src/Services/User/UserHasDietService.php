<?php

namespace App\Services\User;


use App\Entity\Diet\Diet;
use App\Entity\User\User;
use App\Entity\User\UserHasDiet;
use App\Repository\User\UserHasDietRepository;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHasDietService
{
    private UserHasDietRepository $userHasDietRepository;


        public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $encoder,
    )
    {
        $this->userHasDietRepository = $em->getRepository(UserHasDiet::class);
    }


    /**
     * Create a new UserHasDiet entity.
     *
     * @param User $user
     * @param Diet $diet
     * @return UserHasDiet
     */
    public function create(
        User $user,
        Diet $diet,
    ): UserHasDiet {
        return $this->userHasDietRepository->create(
            $user,
            $diet,
        );
    }

    /**
     * Edit an existing UserHasDiet entity.
     *
     * @param UserHasDiet $userHasDiet
     * @param User $user
     * @param Diet $diet
     * @return UserHasDiet
     */
    public function edit(
        UserHasDiet $userHasDiet,
        User $user,
        Diet $diet
    ): UserHasDiet {
        return $this->userHasDietRepository->edit(
            $userHasDiet,
            $user,
            $diet
        );
    }


    /**
     * Remove a UserHasDiet entity.
     *
     * @param UserHasDiet $userHasDiet
     */
    public function remove(UserHasDiet $userHasDiet): void
    {
        $this->userHasDietRepository->remove($userHasDiet);
    }

    /**
     * list
     * 
     * @param FilterService $filterService
     * @return array
     */
    public function list(FilterService $filterService): array
    {
        return $this->userHasDietRepository->list(
            $filterService
        );
    }


    /**
     * Get a UserHasDiet entity by its ID.
     *
     * @param string $id
     * @return UserHasDiet|null
     */
    public function getById(string $id, ?bool $array = false): null|UserHasDiet|array
    {
        return $this->userHasDietRepository->findSimpleDietById($id, $array);
    }

    /**
     * by name
     */
    public function getByName(string $name, bool $array = false): ?UserHasDiet
    {
        return $this->userHasDietRepository-> findByName(
            $name,
            $array
        );
    }



    /**
     * EN: SERVICE TO DELETE ALL USER-DIET RELATIONS BY DIET ID
     * ES: SERVICIO PARA ELIMINAR TODAS LAS RELACIONES USUARIO-DIETA POR ID DE DIETA
     *
     * @param string $dietId
     * @return void
     */
    public function deleteByDietId(string $dietId): void
    {
        $this->userHasDietRepository->deleteByDietId($dietId);
    }

    // ----------------------------------------------------------------
    /**
     * EN: SERVICE TO TOGGLE A USER HAS DIET
     * ES: SERVICIO PARA TOGGLEAR UN USER HAS DIET
     *
     * @param UserHasDiet $userHasDiet
     * @return UserHasDiet|string|null
     */
    // ----------------------------------------------------------------
    public function toggle(UserHasDiet $userHasDiet): UserHasDiet|null|string
    {
        return $this->userHasDietRepository->toggleUserHasDiet(
            $userHasDiet
        );
    }

    /**
     * Get a UserHasDiet entity by its ID.
     *
     * @param string $id
     * @return UserHasDiet|null
     */
    public function getUserHasDietByUserAndSelectedDiet(string $userId, bool $selectedDiet): null|UserHasDiet|array
    {
        return $this->userHasDietRepository->findUserHasDietByUserAndSelectedDiet($userId, $selectedDiet);
    }

        public function deactivateAllUserDiets(string $userId): void
        {
            $this->userHasDietRepository->deactivateAllUserDiets($userId);
        }

    /**
     * EN: SERVICE TO FIND USER-DIET RELATION BY USER AND DIET
     * ES: SERVICIO PARA ENCONTRAR RELACIÓN USUARIO-DIETA POR USUARIO Y DIETA
     *
     * @param string $userId
     * @param string $dietId
     * @return UserHasDiet|null
     */
    public function findByUserAndDiet(string $userId, string $dietId): ?UserHasDiet
    {
        return $this->userHasDietRepository->findByUserAndDiet($userId, $dietId);
    }
}