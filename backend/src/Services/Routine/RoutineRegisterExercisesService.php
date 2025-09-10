<?php

namespace App\Services\Routine;

use App\Entity\Exercise\Exercise;
use App\Entity\Routine\RoutineRegister;
use App\Entity\Routine\RoutineRegisterExercises;
use App\Repository\Exercise\ExerciseRepository;
use App\Repository\Routine\RoutineRegisterExercisesRepository;
use App\Repository\Routine\RoutineRegisterRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
 use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RoutineRegisterExercisesService
{
    /**
     * @var ExerciseRepository|EntityRepository
     */
    protected ExerciseRepository|EntityRepository $exerciseRepository;

    /**
     * @var EntityRepository|RoutineRegisterRepository
     */
    protected EntityRepository|RoutineRegisterRepository $routineRegisterRepository;

    /**
     * @var EntityRepository|RoutineRegisterExercisesRepository
     */
    protected EntityRepository|RoutineRegisterExercisesRepository $routineRegisterExercisesRepository;


    private MessageBusInterface $bus;


    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
        MessageBusInterface $bus
    )
    {
        $this->exerciseRepository = $em->getRepository(Exercise::class);
        $this->routineRegisterRepository = $em->getRepository(RoutineRegister::class);
        $this->routineRegisterExercisesRepository = $em->getRepository(RoutineRegisterExercises::class);
        $this->bus = $bus;
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE BY ID
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR ID
     *
     * @param string $routineRegisterExerciseId
     * @param bool $array
     * @return RoutineRegisterExercises|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineRegisterExerciseById(string $routineRegisterExerciseId, ?bool $array = false): null|RoutineRegisterExercises|array
    {
        return $this->routineRegisterExercisesRepository->findById($routineRegisterExerciseId, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST ROUTINES REGISTER EXERCISES
     * ES: SERVICIO PARA LISTAR LAS RUTINAS REGISTRADAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listRoutineRegisterExercisesService(FilterService $filterService): array
    {
        return $this->routineRegisterExercisesRepository->list($filterService);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A ROUTINE REGISTER EXERCISE
     * ES: SERVICIO PARA CREAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegister $routineRegister
     * @param Exercise $exercise
     * @param int $reps
     * @param float|null $weight
     * @return RoutineRegisterExercises
     */
    // ------------------------------------------------------------------------
    public function createRoutineRegisterExercisesService(
        RoutineRegister $routineRegister,
        Exercise $exercise,
        int $sets,
        int $reps,
        ?float $weight = null
    ): RoutineRegisterExercises
    {

        $routineRegisterExercise = $this->routineRegisterExercisesRepository->create(
            routineRegister: $routineRegister,
            exercise: $exercise,
            sets: $sets,
            reps: $reps,
            weight: $weight
        );

        return $routineRegisterExercise;
    }
    // ------------------------------------------------------------------------


      // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROUTINE REGISTER EXERCISE
     * ES: SERVICIO PARA EDITAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegisterExercises $routineRegisterExercise
     * @param RoutineRegister $routineRegister
     * @param Exercise $exercise
     * @param int $reps
     * @param float|null $weight
     * @return RoutineRegisterExercises|null
     */
    // ------------------------------------------------------------------------
    public function editRoutineRegisterExercisesService(
        RoutineRegisterExercises $routineRegisterExercise,
        int $reps,
        ?float $weight = null
    ): RoutineRegisterExercises|null
    {
        $routineRegisterEdited = $this->routineRegisterExercisesRepository->edit(
            routineRegisterExercises: $routineRegisterExercise,
            reps: $reps,
            weight: $weight
        );

        return $routineRegisterEdited;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A ROUTINE REGISTER EXERCISE
     * ES: SERVICIO PARA ELIMINAR UN REGISTRO DE RUTINA
     *
     * @param RoutineRegisterExercises $routineRegisterExercise
     * @return RoutineRegisterExercises|null
     */
    // ------------------------------------------------------------------------
    public function deleteRoutineRegisterExerciseService(RoutineRegisterExercises $routineRegisterExercise): RoutineRegisterExercises|null
    {
        return $this->routineRegisterExercisesRepository->remove($routineRegisterExercise);
    }
    // ------------------------------------------------------------------------
}