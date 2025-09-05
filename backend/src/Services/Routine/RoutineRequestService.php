<?php

namespace App\Services\Routine;

use App\Request\Routine\CreateRoutineCategoryRequest;
use App\Request\Routine\CreateRoutineRequest;
use App\Request\Routine\DeleteRoutineCategoryRequest;
use App\Request\Routine\DeleteRoutineRequest;
use App\Request\Routine\EditRoutineCategoryRequest;
use App\Request\Routine\EditRoutineRequest;
use App\Request\Routine\GetRoutineCategoryRequest;
use App\Request\Routine\GetRoutineRequest;
use App\Request\Routine\GetRoutineWithDaysRequest;
use App\Request\Routine\ListRoutineCategoriesRequest;
use App\Request\Routine\ListRoutinesRequest;
use App\Request\Routine\ListRoutineExercisesRequest;
use App\Request\Routine\ToggleRoutineCategoryRequest;
use App\Request\Routine\ToggleRoutineRequest;
use App\Request\User\AssignRoutineToUserRequest;
use App\Services\Document\DocumentService;
use App\Services\Exercise\ExerciseService;
use App\Services\User\RoleService;
use App\Services\User\UserHasRoutineService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoutineRequestService extends JWTHandlerService
{

    public function __construct(
        protected RoutineService $routineService,
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DocumentService $documentService,
        protected RoleService $roleService,
        protected ExerciseService $exerciseService,
        protected UserHasRoutineService $userHasRoutineService
    )
    {
        parent::__construct($token, $jwtManager);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE CATEGORIES REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE CATEGORÍAS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE ROUTINE CATEGORY
     * ES: PETICIÓN PARA OBTENER LA CATEGORÍA DE RUTINA
     *
     * @param GetRoutineCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getRoutineCategoryRequestService(GetRoutineCategoryRequest $request): APIJsonResponse
    {
        $data = $this->routineService->getRoutineCategoryById($request->routineCategoryId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Categoria de rutina obtenida con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST ROUTINE CATEGORIES WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR CATEGORÍAS DE RUTINAS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListRoutineCategoriesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listRoutineCategoriesRequestService(ListRoutineCategoriesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->routineService->listRoutineCategoriesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de categorias de rutinas obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A ROUTINE CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UNA CATEGORÍA DE RUTINAS CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateRoutineCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function createRoutineCategoryRequestService(CreateRoutineCategoryRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());

        $this->routineService->createRoutineCategoryService (
            name: $request->name,
            description: $request->description,
            user: $user,
            
        );

        return new APIJsonResponse(
            [],
            true,
            'Categoria de rutina creada con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT A ROUTINE CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UNA CATEGORÍA DE RUTINA CON LOS DATOS PROPORCIONADOS
     *
     * @param EditRoutineCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function editRoutineCategoryRequestService(EditRoutineCategoryRequest $request): APIJsonResponse
    {
        $routineCategory = $this->routineService->getRoutineCategoryByIdSimple($request->routineCategoryId);

        $this->routineService->editRoutineCategoryService(
            routineCategory: $routineCategory,
            name: $request->name,
            description: $request->description,
        );

        return new APIJsonResponse(
            [],
            true,
            'Categoria de rutina editada con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A ROUTINE CATEGORY
     * ES: PETICIÓN PARA ELIMINAR UNA CATEGORÍA DE RUTINA
     *
     * @param DeleteRoutineCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteRoutineCategoryRequestService(DeleteRoutineCategoryRequest $request): APIJsonResponse
    {
        $routineCategory = $this->routineService->getRoutineCategoryByIdSimple($request->routineCategoryId);

        $this->routineService->deleteRoutineCategoryService($routineCategory);

        return new APIJsonResponse(
            [],
            true,
            'Categoria de rutina eliminada con éxito'
        );
    }
    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO TOGGLE THE ROUTINE CATEGORY STATUS
     * ES: PETICIÓN PARA CAMBIAR EL ESTADO DE LA CATEGORÍA DE RUTINA
     *
     * @param ToggleRoutineCategoryRequest $request
     * @throws NonUniqueResultException
     * @return APIJsonResponse
     */
    // -------------------------------------------------------
    public function toggleRoutineCategoryRequestService(ToggleRoutineCategoryRequest $request): APIJsonResponse
    {
        $routineCategory = $this->routineService->getRoutineCategoryByIdSimple($request->routineCategoryId);

        $this->routineService->toggleRoutineCategoryStatusService($routineCategory);

        return new APIJsonResponse(
            [],
            true,
            'Estado de categoria de rutina cambiado con éxito'
        );
    }
    // -------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: ROUTINE REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE RUTINAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE ROUTINE
     * ES: PETICIÓN PARA OBTENER LA RUTINA
     *
     * @param GetRoutineRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getRoutineRequestService(GetRoutineRequest $request): APIJsonResponse
    {
        $data = $this->routineService->getRoutineById($request->routineId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Rutina obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST ROUTINE WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR RUTINAS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListRoutinesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listRoutinesRequestService(ListRoutinesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        if($this->getUser()->isSuperAdmin() || $this->getUser()->isAdmin())
        {
            $filterService->addFilter('isUser', null);
        }else
        {
            $filterService->addFilter('isUser', $this->getUser()->getId());
        }

        $data = $this->routineService->listRoutinesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de rutinas obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A ROUTINE WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UNA RUTINA CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateRoutineRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // -----------------------------------------------------------
    public function createRoutineRequestService(CreateRoutineRequest $request): APIJsonResponse
    {
        /**
         * @var \App\Entity\User\User $user
         */
        $user = $this->getUser();
        $routineCategory = $this->routineService->getRoutineCategoryByIdSimple($request->routineCategoryId);

        $routine = $this->routineService->createRoutineService(
            name: $request->name,
            description: $request->description,
            routineCategory: $routineCategory,
            user: $user,
            quantity: count($request->routineExercises),
            toGainMuscle: $request->toGainMuscle,
            toLoseWeight: $request->toLoseWeight,
            toMaintainWeight: $request->toMaintainWeight,
            toImprovePhysicalHealth: $request->toImprovePhysicalHealth,
            toImproveMentalHealth: $request->toImproveMentalHealth,
            fixShoulder: $request->fixShoulder,
            fixKnees: $request->fixKnees,
            fixBack: $request->fixBack,
            rehab: $request->rehab
        );

        foreach ($request->routineExercises as $index => $routineExercise) {
            $day = $routineExercise['day'];
            foreach ($routineExercise['exercises'] as $exercise) {
                $exerciseFinded = $this->exerciseService->getExerciseByIdSimple($exercise['exerciseId']);
                if (!$exerciseFinded) {
                    $this->routineService->deleteRoutineService($routine);
                    return new APIJsonResponse(
                        [],
                        false,
                        'Por favor, selecciona un ejercicio valido' 
                    );
                }
                try{
                    $this->routineService->createRoutineHasExerciseService(
                        $routine,
                        $exerciseFinded,
                        $exercise['sets'],
                        $exercise['reps'],
                        $exercise['restTime'],
                        $day
                    );
                }catch (APIException $e) {
                    $this->routineService->deleteRoutineService($routine);
                    return new APIJsonResponse(
                        [],
                        false,
                        'Error al crear el ejercicio: ' . $e->getMessage()
                    );
                }
            }
        }

        // Handle manual assignment if userId is provided
        if($routine && $request->userId) {
            $assignUser = $this->userService->getUserByIdSimple($request->userId);
            if (!$assignUser) {
                $this->routineService->deleteRoutineService($routine);
                return new APIJsonResponse(
                    [],
                    false,
                    'Por favor, selecciona un usuario valido'
                );
            }
            $this->userHasRoutineService->create($assignUser, $routine);
        }

        // Handle automatic assignment based on flags (only for admins)
        if ($routine && ($user->isSuperAdmin() || $user->isAdmin())) {
            $this->assignRoutineToMatchingUsers($routine, $request);
        }

        return new APIJsonResponse(
            [],
            true,
            'Rutina creada con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT A ROUTINE WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UNA RUTINA CON LOS DATOS PROPORCIONADOS
     *
     * @param EditRoutineRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException|APIException
     */
    // ------------------------------------------------------------
    public function editRoutineRequestService(EditRoutineRequest $request): APIJsonResponse
    {
        $routine = $this->routineService->getRoutineByIdSimple($request->routineId);
        $routineCategory = $this->routineService->getRoutineCategoryByIdSimple($request->routineCategoryId);

        if (!$routine) {
            throw new APIException('La rutina no existe', code: 404);
        }

    $this->routineService->editRoutineService(
        quantity: count($request->routineExercises), 
        toGainMuscle: $request->toGainMuscle,
        routine: $routine,
        name: $request->name,
        description: $request->description,
        routineCategory: $routineCategory,
        toLoseWeight: $request->toLoseWeight,
        toMaintainWeight: $request->toMaintainWeight,
        toImprovePhysicalHealth: $request->toImprovePhysicalHealth,
        toImproveMentalHealth: $request->toImproveMentalHealth,
        fixShoulder: $request->fixShoulder,
        fixKnees: $request->fixKnees,
        fixBack: $request->fixBack,
        rehab: $request->rehab
    );

        foreach ($routine->getRoutineHasExercise() as $routineExercise) {
            $this->routineService->deleteRoutineHasExerciseService($routineExercise);
        }

        foreach ($request->routineExercises as $routineExercise) {
            $day = $routineExercise['day'];
            foreach ($routineExercise['exercises'] as $exerciseData) {
                $exercise = $this->exerciseService->getExerciseByIdSimple($exerciseData['exerciseId']);

                if (!$exercise) {
                    continue;
                }

                $this->routineService->createRoutineHasExerciseService(
                    $routine,
                    $exercise,
                    $exerciseData['sets'],
                    $exerciseData['reps'],
                    $exerciseData['restTime'],
                    $day
                );
            }
        }

        // Handle automatic assignment based on flags (only for admins)
        $user = $this->getUser();
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Remove existing assignments and reassign based on new flags
            $this->userHasRoutineService->deleteByRoutineId($routine->getId());
            $this->assignRoutineToMatchingUsers($routine, $request);
        }

        return new APIJsonResponse([], true, 'Rutina editada con éxito');
    }

    /**
     * Assign routine to users with matching flags
     */
    private function assignRoutineToMatchingUsers($routine, $request): void
    {
        // Only assign if at least one flag is true
        $hasAnyFlag = $request->toGainMuscle || $request->toLoseWeight || $request->toMaintainWeight ||
                      $request->toImprovePhysicalHealth || $request->toImproveMentalHealth ||
                      $request->fixShoulder || $request->fixKnees || $request->fixBack || $request->rehab;

        if (!$hasAnyFlag) {
            return; 
        }

        // Get users with matching flags
        $matchingUsers = $this->userService->getUsersByFlags(
            $request->toGainMuscle ?? false,
            $request->toLoseWeight ?? false,
            $request->toMaintainWeight ?? false,
            $request->toImprovePhysicalHealth ?? false,
            $request->toImproveMentalHealth ?? false,
            $request->fixShoulder ?? false,
            $request->fixKnees ?? false,
            $request->fixBack ?? false,
            $request->rehab ?? false
        );

        foreach ($matchingUsers as $user) {
            try {
                $this->userHasRoutineService->create($user, $routine);
            } catch (Exception $e) {
                error_log("Error assigning routine {$routine->getId()} to user {$user->getId()}: " . $e->getMessage());
            }
        }
    }

    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A ROUTINE
     * ES: PETICIÓN PARA ELIMINAR UNA RUTINA
     *
     * @param DeleteRoutineRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteRoutineRequestService(DeleteRoutineRequest $request): APIJsonResponse
    {
        $routine = $this->routineService->getRoutineByIdSimple($request->routineId);

        $this->routineService->deleteRoutineService($routine);

        return new APIJsonResponse(
            [],
            true,
            'Rutina eliminado con éxito'
        );
    }
    // -------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO TOGGLE THE ROUTINE STATUS
     * ES: PETICIÓN PARA CAMBIAR EL ESTADO DE LA RUTINA
     *
     * @param ToggleRoutineRequest $request
     * @throws NonUniqueResultException
     * @return APIJsonResponse
     */
    // -------------------------------------------------------
    public function toggleRoutineRequestService(ToggleRoutineRequest $request): APIJsonResponse
    {
        $routine = $this->routineService->getRoutineByIdSimple($request->routineId);

        $this->routineService->toggleRoutineStatusService($routine);

        return new APIJsonResponse(
            [],
            true,
            'Estado de rutina cambiado con éxito'
        );
    }
    // -------------------------------------------------------

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXTERNAL REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES EXTERNAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST ROUTINE EXERCISES WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR EJERCICIOS DE RUTINAS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListRoutineExercisesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listRoutineExercisesRequestService(ListRoutineExercisesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->exerciseService->listExercisesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de ejercicios para rutinas obtenido con exito'
        );
    }
    // -----------------------------------------------------------

    /**
     * EN: REQUEST TO GET A ROUTINE STRUCTURED BY DAYS
     * ES: PETICIÓN PARA OBTENER UNA RUTINA ESTRUCTURADA POR DÍAS
     *
     * @param GetRoutineWithDaysRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    public function getRoutineWithDaysRequestService(GetRoutineWithDaysRequest $request): APIJsonResponse
    {
    $data = $this->routineService->getRoutineWithDays($request->routineId);

    return new APIJsonResponse(
        $data,
        true,
        'Rutina estructurada por días obtenida con éxito'
    );
    }
    // -----------------------------------------------------------

     /**
     * EN: REQUEST TO GET A ROUTINE FOR EDITTING
     * ES: PETICIÓN PARA OBTENER UNA RUTINA PARA EDITAR
     *
     * @param GetRoutineWithDaysRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    public function getRoutineForEditRequestService(GetRoutineWithDaysRequest $request): APIJsonResponse
    {
    $data = $this->routineService->getRoutineForEdit($request->routineId);

    return new APIJsonResponse(
        $data,
        true,
        'Rutina obtenida con éxito para editar'
    );
    }
    // -----------------------------------------------------------

    /**
     * EN: REQUEST TO ASSIGN A ROUTINE TO A USER
     * ES: PETICIÓN PARA ASIGNAR UNA RUTINA A UN USUARIO
     *
     * @param AssignRoutineToUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    public function assignRoutineToUserRequestService(AssignRoutineToUserRequest $request): APIJsonResponse
    {
        $routine = $this->routineService->getRoutineByIdSimple($request->routineId);

        $this->userHasRoutineService->deleteByRoutineId($routine->getId());

        foreach ($request->userIds as $userId) {
            $user = $this->userService->getUserByIdSimple($userId);
            if (!$user) {
                throw new APIException('Usuario no encontrado', code: 404);
            }
        
             if( $routine && $user) {
            $this->userHasRoutineService->create($user, $routine);
            } else {
                throw new APIException('Rutina o usuario no encontrado', code: 404);
            }
        }
       

        return new APIJsonResponse(
            [],
            true,
            'Rutina asignada al usuario con éxito'
        );
    }
    // -----------------------------------------------------------

}