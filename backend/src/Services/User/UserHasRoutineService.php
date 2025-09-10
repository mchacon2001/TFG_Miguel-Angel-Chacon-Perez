<?php

namespace App\Services\User;

use App\Entity\Routine\Routine;
use App\Entity\User\User;
use App\Entity\User\UserHasRoutine;
use App\Repository\User\UserHasRoutineRepository;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHasRoutineService
{
    private UserHasRoutineRepository $userHasRoutineRepository;


        public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $encoder,
    )
    {
        $this->userHasRoutineRepository = $em->getRepository(UserHasRoutine::class);
    }


    /**
     * Create a new UserHasRoutine entity.
     *
     * @param User $user
     * @param Routine $routine
     * @return UserHasRoutine
     */
    public function create(
        User $user,
        Routine $routine,
    ): UserHasRoutine {
        return $this->userHasRoutineRepository->create(
            $user,
            $routine,
        );
    }

    /**
     * Edit an existing UserHasRoutine entity.
     *
     * @param UserHasRoutine $userHasRoutine
     * @param User $user
     * @param Routine $routine
     * @return UserHasRoutine
     */
    public function edit(
        UserHasRoutine $userHasRoutine,
        User $user,
        Routine $routine
    ): UserHasRoutine {
        return $this->userHasRoutineRepository->edit(
            $userHasRoutine,
            $user,
            $routine
        );
    }


    /**
     * Remove a UserHasRoutine entity.
     *
     * @param UserHasRoutine $userHasRoutine
     */
    public function remove(UserHasRoutine $userHasRoutine): void
    {
        $this->userHasRoutineRepository->remove($userHasRoutine);
    }

    /**
     * list
     * 
     * @param FilterService $filterService
     * @return array
     */
    public function list(FilterService $filterService): array
    {
        return $this->userHasRoutineRepository->list(
            $filterService
        );
    }


    /**
     * Get a UserHasRoutine entity by its ID.
     *
     * @param string $id
     * @return UserHasRoutine|null
     */
    public function getById(string $id, ?bool $array = false): null|UserHasRoutine|array
    {
        return $this->userHasRoutineRepository->findSimpleRoutineById($id, $array);
    }

    /**
     * by name
     */
    public function getByName(string $name, bool $array = false): ?UserHasRoutine
    {
        return $this->userHasRoutineRepository-> findByName(
            $name,
            $array
        );
    }



    public function deleteByRoutineId(string $routineId): void
    {
        $userHasRoutines = $this->userHasRoutineRepository->findBy(['routine' => $routineId]);

        foreach ($userHasRoutines as $userHasRoutine) {
            $this->remove($userHasRoutine);
        }
    }

}