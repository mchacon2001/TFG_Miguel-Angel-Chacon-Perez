<?php

namespace App\Services\Diet;


use App\Entity\Diet\DailyIntake;
use App\Entity\Diet\Diet;
use App\Entity\Food\Food;
use App\Entity\User\User;
use App\Repository\Diet\DailyIntakeRepository;
use App\Utils\Tools\FilterService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DailyIntakeService
{
    private DailyIntakeRepository $dailyIntakeRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $encoder,
        
    )
    {
        $this->dailyIntakeRepository = $em->getRepository(DailyIntake::class);
    }


    /**
     * Create a new DailyIntake entity.
     *
     * @param User $user
     * @param Diet $diet
     * @return DailyIntake
     */
    public function create(
        User $user,
        Food $food,
        string $mealType,
        float $quantity
    ): DailyIntake {
        return $this->dailyIntakeRepository->create(
            user: $user,
            food: $food,
            mealType: $mealType,
            quantity: $quantity
        );
    }

    /**
     * Edit an existing DailyIntake entity.
     *
     * @param DailyIntake $dailyIntake
     * @param User $user
     * @param Diet $diet
     * @return DailyIntake
     */
    public function edit(
        DailyIntake $dailyIntake,
        User $user,
        Diet $diet
    ): DailyIntake {
        return $this->dailyIntakeRepository->edit(
            $dailyIntake,
            $user,
            $diet
        );
    }


    /**
     * Remove a DailyIntake entity.
     *
     * @param DailyIntake $dailyIntake
     */
    public function remove(DailyIntake $dailyIntake): void
    {
        $this->dailyIntakeRepository->remove($dailyIntake);
    }

    /**
     * list DailyIntake entities.
     *
     * @param FilterService $filterService
     * @return array
     */
    public function list(FilterService $filterService): array
    {
        return $this->dailyIntakeRepository->list(
            $filterService
        );
    }


    /**
     * Get daily intakes by user and date
     *
     * @param User $user
     * @param DateTime $date
     * @param bool|null $array
     * @return array
     */
    public function getByUserAndDate(User $user, DateTime $date, ?bool $array = false): array
    {
        return $this->dailyIntakeRepository->findByUserAndDate($user, $date, $array);
    }

    /**
     * Delete daily intakes by user and date
     *
     * @param User $user
     * @param DateTime $date
     * @return void
     */
    public function deleteByUserAndDate(User $user, DateTime $date): void
    {
        $this->dailyIntakeRepository->deleteByUserAndDate($user, $date);
    }

}