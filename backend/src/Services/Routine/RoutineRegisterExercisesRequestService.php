<?php

namespace App\Services\Routine;

use App\Request\Routine\CreateRoutineRegisterExercisesRequest;
use App\Request\Routine\EditRoutineRegisterExercisesRequest;
use App\Request\Routine\DeleteRoutineRegisterExercisesRequest;
use App\Request\Routine\GetRoutineRegisterExercisesRequest;
use App\Request\Routine\ListRoutineRegisterExercisesRequest;
use App\Services\Exercise\ExerciseService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RoutineRegisterExercisesRequestService extends JWTHandlerService
{
    public function __construct(
        protected RoutineRegisterService $routineRegisterService,
        protected RoutineRegisterExercisesService $routineRegisterExercisesService,
        protected ExerciseService $exerciseService,
        protected RoutineService $routineService,
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager
    )
    {
        parent::__construct($token, $jwtManager);
    }


    // -----------------------------------------------------------
    // GET RoutineRegister by ID
    // -----------------------------------------------------------
    public function getById(GetRoutineRegisterExercisesRequest $request): APIJsonResponse
    {
        $data = $this->routineRegisterExercisesService->getRoutineRegisterExerciseById($request->routineRegisterExerciseId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Registro de rutina obtenido con éxito'
        );
    }

    // -----------------------------------------------------------
    // LIST RoutineRegisters
    // -----------------------------------------------------------

    public function list(ListRoutineRegisterExercisesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        // Puedes filtrar por usuario si lo necesitas
        // $filterService->addFilter('user', $this->getUser()->getId());

        $data = $this->routineRegisterService->listRoutinesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de registros de rutina obtenido con éxito'
        );
    }
    
    // -----------------------------------------------------------
    // CREATE RoutineRegisterExercises
    // -----------------------------------------------------------
    public function create(CreateRoutineRegisterExercisesRequest $request): APIJsonResponse
    {
        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($request->routineRegisterId);
        $exercise = $this->exerciseService->getExerciseById($request->exerciseId);

        if (!$routineRegister || !$exercise) {
            throw new APIException('Registro de rutina o ejercicio no existe', code: 404);
        }

        $routineRegisterExercise = $this->routineRegisterExercisesService->createRoutineRegisterExercisesService(
            $routineRegister,
            $exercise,
            $request->sets,
            $request->reps,
            $request->weight
        );

        return new APIJsonResponse(
            $routineRegisterExercise,
            true,
            'Ejercicio de registro de rutina creado con éxito'
        );
    }

    // -----------------------------------------------------------
    // EDIT RoutineRegisterExercises
    // -----------------------------------------------------------
    public function edit(EditRoutineRegisterExercisesRequest $request): APIJsonResponse
    {
        $routineRegisterExercise = $this->routineRegisterExercisesService->getRoutineRegisterExerciseById($request->routineRegisterExerciseId);

        if (!$routineRegisterExercise) {
            throw new APIException('El ejercicio de registro de rutina no existe', code: 404);
        }

        $routineRegisterExercise = $this->routineRegisterExercisesService->editRoutineRegisterExercisesService(
            $routineRegisterExercise,
            $request->reps,
            $request->weight
        );

        return new APIJsonResponse(
            $routineRegisterExercise,
            true,
            'Ejercicio de registro de rutina editado con éxito'
        );
    }

    // -----------------------------------------------------------
    // DELETE RoutineRegisterExercises
    // -----------------------------------------------------------
    public function delete(DeleteRoutineRegisterExercisesRequest $request): APIJsonResponse
    {
        $routineRegisterExercise = $this->routineRegisterExercisesService->getRoutineRegisterExerciseById($request->routineRegisterExerciseId);

        if (!$routineRegisterExercise) {
            throw new APIException('El ejercicio de registro de rutina no existe', code: 404);
        }

        $this->routineRegisterExercisesService->deleteRoutineRegisterExerciseService($routineRegisterExercise);

        return new APIJsonResponse(
            [],
            true,
            'Ejercicio de registro de rutina eliminado con éxito'
        );
    }
}