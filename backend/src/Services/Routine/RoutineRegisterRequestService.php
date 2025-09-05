<?php

namespace App\Services\Routine;

use App\Request\Routine\CreateRoutineRegisterRequest;
use App\Request\Routine\EditRoutineRegisterRequest;
use App\Request\Routine\DeleteRoutineRegisterRequest;
use App\Request\Routine\FinishRoutineRegisterRequest;
use App\Request\Routine\GetActiveRoutineRegisterByUserAndRoutineRequest;
use App\Request\Routine\GetRoutineRegisterRequest;
use App\Request\Routine\ListRoutineRegisterRequest;
use App\Services\Exercise\ExerciseService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RoutineRegisterRequestService extends JWTHandlerService
{
    public function __construct(
        protected RoutineRegisterService $routineRegisterService,
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
    public function getById(GetRoutineRegisterRequest $request): APIJsonResponse
    {
        $data = $this->routineRegisterService->getRoutineRegisterById($request->routineRegisterId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Registro de rutina obtenido con éxito'
        );
    }

    // -----------------------------------------------------------
    // LIST RoutineRegisters
    // -----------------------------------------------------------
    public function list(ListRoutineRegisterRequest $request): APIJsonResponse
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
    // CREATE RoutineRegister
    // -----------------------------------------------------------
    public function create(CreateRoutineRegisterRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());
        $routine = $this->routineService->getRoutineById($request->routineId);

        if (!$routine) {
            throw new APIException('La rutina no existe', code: 404);
        }

        $routineRegister = $this->routineRegisterService->createRoutineRegisterService(
            $user,
            $routine,
            $request->day
        );

        return new APIJsonResponse(
            [
                'routineRegister' => $routineRegister->getId()
            ],
            true,
            'Registro de rutina creado con éxito'
        );
    }

    // -----------------------------------------------------------
    // EDIT RoutineRegister
    // -----------------------------------------------------------
    public function edit(EditRoutineRegisterRequest $request): APIJsonResponse
    {
        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($request->routineRegisterId);

        if (!$routineRegister) {
            throw new APIException('El registro de rutina no existe', code: 404);
        }

        $routine = $this->routineService->getRoutineById($request->routineId);

        $routineRegister = $this->routineRegisterService->editRoutineRegisterService(
            $routineRegister,
            $this->getUser(),
            $routine,
            $request->startTime,
            $request->endTime
        );

        return new APIJsonResponse(
            $routineRegister,
            true,
            'Registro de rutina editado con éxito'
        );
    }

    // -----------------------------------------------------------
    // DELETE RoutineRegister
    // -----------------------------------------------------------
    public function delete(DeleteRoutineRegisterRequest $request): APIJsonResponse
    {
        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($request->routineRegisterId);

        if (!$routineRegister) {
            throw new APIException('El registro de rutina no existe', code: 404);
        }

        $this->routineRegisterService->deleteRoutineRegisterService($routineRegister);

        return new APIJsonResponse(
            [],
            true,
            'Registro de rutina eliminado con éxito'
        );
    }


    // -----------------------------------------------------------
    // FINISH RoutineRegister
    // -----------------------------------------------------------
    public function finish(FinishRoutineRegisterRequest $request): APIJsonResponse
    {
        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($request->routineRegisterId);

        if (!$routineRegister) {
            throw new APIException('Registro de rutina no existe', code: 404);
        }

        $finishedRoutineRegister = $this->routineRegisterService->finishRoutineRegisterService($routineRegister);

        return new APIJsonResponse(
            $finishedRoutineRegister,
            true,
            'Registro de rutina finalizado con éxito'
        );
    }

    // -----------------------------------------------------------
    // GET Active Routine Register by User and Routine
    // -----------------------------------------------------------
    public function getActiveRoutineRegisterByUserAndRoutine(GetActiveRoutineRegisterByUserAndRoutineRequest $request): APIJsonResponse
    {
        
        $user = $this->userService->getUserByIdSimple($request->userId);
        $routine = $this->routineService->getRoutineById($request->routineId);
        
        if (!$user) {
            throw new APIException('Usuario no existe', code: 404);
        }
        if (!$routine) {
            throw new APIException('Rutina no existe', code: 404);
        }

        $activeRoutineRegister = $this->routineRegisterService->getActiveRoutineRegisterByUserAndRoutine($user, $routine);
        
        if ($activeRoutineRegister) {            
            // Convertir la entidad a array para la serialización
            $serializedData = [
                'id' => $activeRoutineRegister->getId(),
                'day' => $activeRoutineRegister->getDay(),
                'startTime' => $activeRoutineRegister->getStartTime(),
                'endTime' => $activeRoutineRegister->getEndTime(),
                'createdAt' => $activeRoutineRegister->getCreatedAt(),
                'updatedAt' => $activeRoutineRegister->getUpdatedAt(),
                'routine' => [
                    'id' => $activeRoutineRegister->getRoutines()->getId(),
                    'name' => $activeRoutineRegister->getRoutines()->getName(),
                    'description' => $activeRoutineRegister->getRoutines()->getDescription(),
                    'quantity' => $activeRoutineRegister->getRoutines()->getQuantity(),
                ],
                'user' => [
                    'id' => $activeRoutineRegister->getUser()->getId(),
                    'name' => $activeRoutineRegister->getUser()->getName(),
                    'email' => $activeRoutineRegister->getUser()->getEmail(),
                ]
            ];
            
            
            return new APIJsonResponse(
                $serializedData,
                true,
                'Registro de rutina activo obtenido con éxito'
            );
        } else {
            return new APIJsonResponse(
                null,
                false,
                'No hay rutina activa para este usuario y rutina'
            );
        }
    }
}