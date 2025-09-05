<?php

namespace App\Services\Routine;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineRegister;
use App\Entity\User\User;
use App\Repository\Routine\RoutineRegisterRepository;
use App\Repository\Routine\RoutineRepository;
use App\Repository\User\UserRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
 use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RoutineRegisterService
{
    /**
     * @var UserRepository|EntityRepository
     */
    protected UserRepository|EntityRepository $userRepository;

    /**
     * @var RoutineRepository|EntityRepository
     */
    protected RoutineRepository|EntityRepository $routineRepository;
    /**
     * @var EntityRepository|RoutineRegisterRepository
     */
    protected EntityRepository|RoutineRegisterRepository $routineRegisterRepository;


    private MessageBusInterface $bus;


    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
        MessageBusInterface $bus
    )
    {
        $this->userRepository = $em->getRepository(User::class);
        $this->routineRepository = $em->getRepository(Routine::class);
        $this->routineRegisterRepository = $em->getRepository(RoutineRegister::class);
        $this->bus = $bus;
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE BY ID
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR ID
     *
     * @param string $routineRegisterId
     * @param bool $array
     * @return RoutineRegister|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineRegisterById(string $routineRegisterId, ?bool $array = false): null|RoutineRegister|array
    {
        return $this->routineRegisterRepository->findById($routineRegisterId, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST ROUTINES REGISTER
     * ES: SERVICIO PARA LISTAR LAS RUTINAS REGISTRADAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listRoutinesService(FilterService $filterService): array
    {
        return $this->routineRegisterRepository->list($filterService);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A ROUTINE REGISTER
     * ES: SERVICIO PARA CREAR UN REGISTRO DE RUTINA
     *
     * @param User $user
     * @param Routine $routine
     * @return RoutineRegister
     */
    // ------------------------------------------------------------------------
    public function createRoutineRegisterService(
        User $user,
        Routine $routine,
        int $day
    ): RoutineRegister
    {
 
        $routineRegister = $this->routineRegisterRepository->create(
            user: $user,
            routine: $routine,
            day: $day
        );

        return $routineRegister;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROUTINE REGISTER
     * ES: SERVICIO PARA EDITAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegister $routineRegister
     * @param User $user
     * @param Routine $routine
     * @param int $startTime
     * @param int|null $endTime
     * @return RoutineRegister|null
     */
    // ------------------------------------------------------------------------

    public function editRoutineRegisterService(
        RoutineRegister $routineRegister,
        User $user,
        Routine $routine,
        int $startTime,
        ?int $endTime = null
    ): RoutineRegister|null
    {
        $routineRegisterEdited = $this->routineRegisterRepository->edit(
            routineRegister: $routineRegister,
            user: $user,
            routine: $routine,
            startTime: $startTime,
            endTime: $endTime
        );
        
        return $routineRegisterEdited;
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A ROUTINE
     * ES: SERVICIO PARA ELIMINAR UNA RUTINA
     *
     * @param RoutineRegister $routineRegister
     * @return Routine|null
     */
    // ------------------------------------------------------------------------
    public function deleteRoutineRegisterService(RoutineRegister $routineRegister): RoutineRegister|null
    {
        return $this->routineRegisterRepository->remove($routineRegister);
    }
    // ------------------------------------------------------------------------

    /**
     * EN: SERVICE TO FINISH A ROUTINE REGISTER
     * ES: SERVICIO PARA FINALIZAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegister $routineRegister
     * @return RoutineRegister
     */
    public function finishRoutineRegisterService(RoutineRegister $routineRegister): RoutineRegister
    {
        return $this->routineRegisterRepository->finish($routineRegister);
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ACTIVE ROUTINE REGISTER BY USER AND ROUTINE
     * ES: SERVICIO PARA OBTENER EL REGISTRO DE RUTINA ACTIVO POR USUARIO Y RUTINA
     * @param User $user
     * @param Routine $routine
     * @return RoutineRegister|null
     */
    // ------------------------------------------------------------------------
    public function getActiveRoutineRegisterByUserAndRoutine(User $user, Routine $routine): RoutineRegister|null
    {
        return $this->routineRegisterRepository->getActiveRoutineRegisterByUserAndRoutine($user, $routine);
    }
    // ------------------------------------------------------------------------
}