<?php

namespace App\Services\Routine;

use App\Entity\Routine\Routine;
use App\Entity\Routine\RoutineCategory;
use App\Entity\Routine\RoutineHasExercise;
use App\Entity\Exercise\Exercise;
use App\Entity\User\User;
use App\Repository\Routine\RoutineCategoryRepository;
use App\Repository\Routine\RoutineHasExerciseRepository;
use App\Repository\Routine\RoutineRepository;
use App\Repository\Exercise\ExerciseRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RoutineService
{
    /**
     * @var ExerciseRepository|EntityRepository
     */
    protected ExerciseRepository|EntityRepository $exerciseRepository;

    /**
     * @var RoutineRepository|EntityRepository
     */
    protected RoutineRepository|EntityRepository $routineRepository;

    /**
     * @var RoutineCategoryRepository|EntityRepository
     */
    protected RoutineCategoryRepository|EntityRepository $routineCategoryRepository;

    /**
     * @var RoutineHasExerciseRepository|EntityRepository
     */
    protected RoutineHasExerciseRepository|EntityRepository $routineHasExerciseRepository;

    private MessageBusInterface $bus;


    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
        MessageBusInterface $bus
    )
    {
        $this->exerciseRepository = $em->getRepository(Exercise::class);
        $this->routineCategoryRepository = $em->getRepository(RoutineCategory::class);
        $this->routineRepository = $em->getRepository(Routine::class);
        $this->routineHasExerciseRepository = $em->getRepository(RoutineHasExercise::class);
        $this->bus = $bus;
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: ROUTINE CATEGORIES SERVICES
    // ES: SERVICIOS DE CATEGORÍAS DE RUTINAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE CATEGORY BY ID
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE RUTINAS POR ID
     *
     * @param string $routineCategoryId
     * @param bool $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineCategoryById(string $routineCategoryId, ?bool $array = false): null|RoutineCategory|array
    {
        return $this->routineCategoryRepository->findById($routineCategoryId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE CATEGORY BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE RUTINAS POR ID (MÉTODO SIMPLE)
     *
     * @param string $routineCategoryId
     * @param bool $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineCategoryByIdSimple(string $routineCategoryId, ?bool $array = false): null|RoutineCategory|array
    {
        return $this->routineCategoryRepository->findSimpleRoutineCategoryById($routineCategoryId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A ROUTINE CATEGORY BY NAME
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE RUTINAS POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return RoutineCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineCategoryByName(string $name, ?bool $array = false): RoutineCategory|array|null
    {
        return $this->routineCategoryRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST ROUTINE CATEGORIES
     * ES: SERVICIO PARA LISTAR LAS CATEGORÍAS DE RUTINAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listRoutineCategoriesService(FilterService $filterService): array
    {
        return $this->routineCategoryRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A ROUTINE CATEGORY
     * ES: SERVICIO PARA CREAR UNA CATEGORÍA DE RUTINAS
     *
     * @param string $name
     * @param string|null $description
     * @param User $user
     * @return RoutineCategory|null
     */
    // ------------------------------------------------------------------------
    public function createRoutineCategoryService(
        string $name,
        ?string $description,
        User $user
    ): RoutineCategory|null
    {
        return $this->routineCategoryRepository->create(
            name: $name,
            description: $description,
            user: $user
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROUTINE CATEGORY
     * ES: SERVICIO PARA EDITAR UNA CATEGORÍA DE RUTINAS
     *
     * @param RoutineCategory $routineCategory
     * @param string $name
     * @param string|null $description
     * @return RoutineCategory|null
     */
    // ------------------------------------------------------------------------
    public function editRoutineCategoryService(
        RoutineCategory $routineCategory,
        string $name,
        ?string $description,
    ): RoutineCategory|null
    {
        return $this->routineCategoryRepository->edit(
            routineCategory: $routineCategory,
            name: $name,
            description: $description,
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A ROUTINE CATEGORY
     * ES: SERVICIO PARA ELIMINAR UNA CATEGORÍA DE RUTINAS
     *
     * @param RoutineCategory $routineCategory
     * @return RoutineCategory|null
     */
    // ------------------------------------------------------------------------
    public function deleteRoutineCategoryService(RoutineCategory $routineCategory): RoutineCategory|null
    {
        return $this->routineCategoryRepository->remove($routineCategory);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO TOGGLE ROUTINE CATEGORY STATUS
     * ES: SERVICIO PARA CAMBIAR EL ESTADO DE UNA CATEGORÍA DE RUTINAS
     *
     * @param RoutineCategory $routineCategory
     * @return RoutineCategory|null
     */
    // ------------------------------------------------------------------------
    public function toggleRoutineCategoryStatusService(RoutineCategory $routineCategory): RoutineCategory|null
    {
        return $this->routineCategoryRepository->toggleStatus($routineCategory);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: ROUTINE SERVICES
    // ES: SERVICIOS DE RUTINAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE BY ID
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR ID
     *
     * @param string $routineId
     * @param bool $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineById(string $routineId, ?bool $array = false): null|Routine|array
    {
        return $this->routineRepository->findById($routineId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR ID (MÉTODO SIMPLE)
     *
     * @param string $routineId
     * @param bool $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineByIdSimple(string $routineId, ?bool $array = false): null|Routine|array
    {
        return $this->routineRepository->findSimpleRoutineById($routineId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ROUTINE BY NAME
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineByName(string $name, ?bool $array = false): Routine|array|null
    {
        return $this->routineRepository->findRoutineByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL ROUTINES
     * ES: SERVICIO PARA OBTENER TODAS LOS RUTINAS
     *
     * @param bool|null $array
     * @return array|Routine
     */
    // ------------------------------------------------------------------------
    public function getAllRoutines(?bool $array = false): array|Routine
    {
        return $this->routineRepository->getAllRoutines($array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A ROUTINE BY NAME
     * ES: SERVICIO PARA OBTENER UNA RUTINA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Routine|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getAllByName(string $name, ?bool $array = false): Routine|array|null
    {
        return $this->routineRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST ROUTINES
     * ES: SERVICIO PARA LISTAR LAS RUTINAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listRoutinesService(FilterService $filterService): array
    {
        return $this->routineRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO FORMAT ROUTINE WITH DAYS
     * ES: SERVICIO PARA FORMATEAR UNA RUTINA CON DÍAS
     *
     * @param Routine $routine
     * @return array
     */
    // ------------------------------------------------------------------------
    
    public function formatRoutineWithDays(Routine $routine): array
{
    $groupedByDay = [];

    foreach ($routine->getRoutineHasExercise() as $rhe) {
        $day = $rhe->getDay();

        if (!isset($groupedByDay[$day])) {
            $groupedByDay[$day] = [
                'dayNumber' => $day,
                'routineHasExercise' => [],
            ];
        }

        $groupedByDay[$day]['routineHasExercise'][] = [
            'sets' => $rhe->getSets(),
            'reps' => $rhe->getReps(),
            'exercise' => [
                'name' => $rhe->getExercise()->getName()
            ]
        ];
    }

    foreach ($groupedByDay as $dayNumber => $dayInfo) {
        $groupedByDay[$dayNumber]['exerciseQuantity'] = count($dayInfo['routineHasExercise']);
    }

    ksort($groupedByDay);

    return [
        'id' => $routine->getId(),
        'name' => $routine->getName(),
        'description' => $routine->getDescription(),
        'quantity' => count($groupedByDay),
        'days' => array_values($groupedByDay),
    ];
}

    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A ROUTINE WITH DAYS
     * ES: SERVICIO PARA OBTENER UNA RUTINA CON DÍAS
     * 
     * @param string $routineId
     * @return array
     * @throws \Exception
     */
    // ------------------------------------------------------------------------
    public function getRoutineWithDays(string $routineId): array
    {
        $routine = $this->getRoutineByIdSimple($routineId);

        if (!$routine) {
            throw new \Exception('Rutina no encontrada');
        }

        return $this->formatRoutineWithDays($routine);
    }

    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO FORMAT ROUTINE FOR EDIT
     * ES: SERVICIO PARA FORMATEAR UNA RUTINA PARA EDITAR
     *
     * @param Routine $routine
     * @return array
     */
    // ------------------------------------------------------------------------
    public function formatRoutineForEdit(Routine $routine): array
    {
        $groupedByDay = [];

        foreach ($routine->getRoutineHasExercise() as $rhe) {
            $day = $rhe->getDay(); // Obtenemos el día del ejercicio

            if (!isset($groupedByDay[$day])) {
                $groupedByDay[$day] = [
                    'day' => $day,
                    'exercises' => [],
                ];
            }

            $groupedByDay[$day]['exercises'][] = [
                'exerciseId' => $rhe->getExercise()->getId(),
                'sets' => $rhe->getSets(),
                'reps' => $rhe->getReps(),
                'restTime' => $rhe->getRestTime(),
            ];
        }

        $routineExercises = array_values($groupedByDay); 

        return [
            'id' => $routine->getId(),
            'active' => $routine->isActive(),
            'name' => $routine->getName(),
            'routineCategoryId' => $routine->getRoutineCategory()->getId(),
            'description' => $routine->getDescription(),
            'routineExercises' => $routineExercises,
            'toGainMuscle' => $routine->isToGainMuscle(),
            'toLoseWeight' => $routine->isToLoseWeight(),
            'toMaintainWeight' => $routine->isToMaintainWeight(),
            'toImprovePhysicalHealth' => $routine->isToImprovePhysicalHealth(),
            'toImproveMentalHealth' => $routine->isToImproveMentalHealth(),
            'fixShoulder' => $routine->isFixShoulder(),
            'fixKnees' => $routine->isFixKnees(),
            'fixBack' => $routine->isFixBack(),
            'rehab' => $routine->isRehab(),
        ];
    }

// ------------------------------------------------------------------------
/**
 * EN: SERVICE TO GET A ROUTINE FOR EDIT
 * ES: SERVICIO PARA OBTENER UNA RUTINA PARA EDITAR
 * 
 * @param string $routineId
 * @return array
 * @throws \Exception
 */
// ------------------------------------------------------------------------

public function getRoutineForEdit(string $routineId): array
{
    $routine = $this->getRoutineByIdSimple($routineId);

    if (!$routine) {
        throw new \Exception('Rutina no encontrada');
    }

    return $this->formatRoutineForEdit($routine);
}

    

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A ROUTINE
     * ES: SERVICIO PARA CREAR UNA RUTINA
     *
     * @param string $name
     * @param string|null $description
     * @param RoutineCategory $routineCategory
     * @param User $user
     * @param int $quantity
     * @return Routine|null
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ------------------------------------------------------------------------
    public function createRoutineService(
        string $name,
        ?string $description,
        RoutineCategory $routineCategory,
        int $quantity,
        User $user,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Routine|null
    {
        $routineCreated =  $this->routineRepository->create(
            name: $name,
            description: $description,
            routineCategory: $routineCategory,
            user: $user,
            quantity: $quantity,
            toGainMuscle: $toGainMuscle,
            toLoseWeight: $toLoseWeight,
            toMaintainWeight: $toMaintainWeight,
            toImprovePhysicalHealth: $toImprovePhysicalHealth,
            toImproveMentalHealth: $toImproveMentalHealth,
            fixShoulder: $fixShoulder,
            fixKnees: $fixKnees,
            fixBack: $fixBack,
            rehab: $rehab
        );
        return $routineCreated;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A ROUTINE
     * ES: SERVICIO PARA EDITAR UNA RUTINA
     *
     * @param Routine $routine
     * @param string $name
     * @param string|null $description
     * @param RoutineCategory $routineCategory
     * @param array|null $oldRoutineExercises
     * @param int $quantity
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Routine|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editRoutineService(
        Routine $routine,
        string $name,
        ?string $description,
        RoutineCategory $routineCategory,
        int $quantity,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Routine|null
    {
        $routineEdited = $this->routineRepository->edit(
            routine: $routine,
            name: $name,
            description: $description,
            routineCategory: $routineCategory,
            quantity: $quantity,
            toGainMuscle: $toGainMuscle,
            toLoseWeight: $toLoseWeight,
            toMaintainWeight: $toMaintainWeight,
            toImprovePhysicalHealth: $toImprovePhysicalHealth,
            toImproveMentalHealth: $toImproveMentalHealth,
            fixShoulder: $fixShoulder,
            fixKnees: $fixKnees,
            fixBack: $fixBack,
            rehab: $rehab
        );
        return $routineEdited;
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A ROUTINE
     * ES: SERVICIO PARA ELIMINAR UNA RUTINA
     *
     * @param Routine $routine
     * @return Routine|null
     */
    // ------------------------------------------------------------------------
    public function deleteRoutineService(Routine $routine): Routine|null
    {
        return $this->routineRepository->remove($routine);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO TOGGLE ROUTINE STATUS
     * ES: SERVICIO PARA CAMBIAR EL ESTADO DE UNA RUTINA
     *
     * @param Routine $routine
     * @return Routine|null
     */
    // ------------------------------------------------------------------------
    public function toggleRoutineStatusService(Routine $routine): Routine|null
    {
        return $this->routineRepository->toggleStatus($routine);
    }
    // ------------------------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: RELATION EXERCISE-ROUTINE SERVICES
    // ES: SERVICIOS DE RELACIÓN EJERCICIO-RUTINA
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET RELATION BY ID
     * ES: SERVICIO PARA OBTENER UNA RELACIÓN POR ID
     *
     * @param string $routineHasExerciseId
     * @param bool $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineHasExerciseById(string $routineHasExerciseId, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->routineHasExerciseRepository->findById($routineHasExerciseId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET RELATION BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA RELACIÓN POR ID (MÉTODO SIMPLE)
     *
     * @param string $routineHasExerciseId
     * @param bool $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getSimpleRoutineHasExerciseById(string $routineHasExerciseId, ?bool $array = false): null|RoutineHasExercise|array
    {
        return $this->routineHasExerciseRepository->findSimpleRoutineHasExerciseById($routineHasExerciseId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE RELATION BY ROUTINE, EXERCISE
     * ES: SERVICIO PARA OBTENER LA RELACIÓN POR RUTINA, EJERCICIO
     *
     * @param string $routineId
     * @param string $exerciseId
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineHasExerciseByRoutineAndExercise(string $routineId, string $exerciseId, ?bool $array = false): RoutineHasExercise|array|null
    {
        return $this->routineHasExerciseRepository->findByRoutineAndExercise($routineId, $exerciseId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE RELATION BY ROUTINE, EXERCISE, EXERCISE IDENTIFIER
     * ES: SERVICIO PARA OBTENER LA RELACIÓN POR RUTINA, EJERCICIO, CÓDIGO DE EJERCICIO
     *
     * @param string $exerciseIdentifier
     * @param string $routineId
     * @param string $exerciseId
     * @param bool|null $array
     * @return RoutineHasExercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getRoutineHasExerciseByExerciseIdentifierAndRoutineAndExercise(string $exerciseIdentifier, string $routineId, string $exerciseId, ?bool $array = false): RoutineHasexercise|array|null
    {
        return $this->routineHasExerciseRepository->findByExerciseIdentifierAndRoutineAndExercise($exerciseIdentifier, $routineId, $exerciseId, $array);
    }
    // ------------------------------------------------------------------------



    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A RELATION
     * ES: SERVICIO PARA CREAR UNA RELACIÓN
     *
     * @param Routine $routine
     * @param Exercise $exercise
     * @param int $sets
     * @param int $reps
     * @param int $restTime
     * @param int $day
     * @return RoutineHasExercise|null
     */
    // ------------------------------------------------------------------------
    public function createRoutineHasExerciseService(
        Routine $routine,
        Exercise $exercise,
        int $sets,
        int $reps,
        int $restTime,
        int $day
    ): RoutineHasExercise|null
    {
        return $this->routineHasExerciseRepository->create(
            exercise: $exercise,
            routine: $routine,
            sets: $sets ?? 4,
            reps: $reps ?? 10,
            restTime: $restTime ?? 30,
            day: $day ?? 1,
        );
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------

     public function deleteRoutineHasExerciseService(
        RoutineHasExercise $routineHasExercise
    ): void
    {
        $this->routineHasExerciseRepository->remove($routineHasExercise);
    }
        
}