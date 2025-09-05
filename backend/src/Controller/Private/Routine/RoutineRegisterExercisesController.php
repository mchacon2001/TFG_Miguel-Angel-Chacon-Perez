<?php

namespace App\Controller\Private\Routine;

use App\Attribute\Permission;
use App\Request\Routine\CreateRoutineRegisterExercisesRequest;
use App\Request\Routine\DeleteRoutineRegisterExercisesRequest;
use App\Request\Routine\EditRoutineRegisterExercisesRequest;
use App\Request\Routine\FinishRoutineRegisterExercisesRequest;
use App\Request\Routine\GetRoutineRegisterExercisesRequest;
use App\Request\Routine\ListRoutineRegisterExercisesRequest;
use App\Services\Routine\RoutineRegisterExercisesRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/routine-register-exercises", name: "routine_register_exercises")]
class RoutineRegisterExercisesController extends AbstractController
{

    public function __construct(
        protected RoutineRegisterExercisesRequestService $routineRegisterExercisesRequestService
    )
    {
    }

    /**
     * EN: END-POINT TO GET A ROUTINE REGISTER EXERCISES
     * ES: END-POINT PARA OBTENER UN REGISTRO DE RUTINA EJERCICIOS
     */

    #[Route('/get', name: 'routine_register_exercises_get', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function get(GetRoutineRegisterExercisesRequest $request): Response
    {
        return $this->routineRegisterExercisesRequestService->getById($request);
    }

    /**
     * EN: END-POINT TO LIST ALL ROUTINE REGISTERS
     * ES: END-POINT PARA LISTAR TODOS LOS REGISTROS DE RUTINA
     */

    #[Route('/list', name: 'routine_register_exercises_list', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function list(ListRoutineRegisterExercisesRequest $request): Response
    {
        return $this->routineRegisterExercisesRequestService->list($request);
    }

    /**
     * EN: END-POINT TO CREATE A ROUTINE REGISTER
     * ES: END-POINT PARA CREAR UN REGISTRO DE RUTINA
     */

    #[Route('/create', name: 'routine_register_exercises_create', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'create')]
    public function create(CreateRoutineRegisterExercisesRequest $request): Response
    {
        return $this->routineRegisterExercisesRequestService->create($request);
    }

    /**
     * EN: END-POINT TO EDIT A ROUTINE REGISTER
     * ES: END-POINT PARA EDITAR UN REGISTRO DE RUTINA
     */

    #[Route('/edit', name: 'routine_register_exercises_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'edit')]
    public function edit(EditRoutineRegisterExercisesRequest $request): Response
    {
        return $this->routineRegisterExercisesRequestService->edit($request);
    }

    /**
     * EN: END-POINT TO DELETE A ROUTINE REGISTER
     * ES: END-POINT PARA ELIMINAR UN REGISTRO DE RUTINA
     */

    #[Route('/delete', name: 'routine_register_exercises_delete', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'delete')]
    public function delete(DeleteRoutineRegisterExercisesRequest $request): Response
    {
        return $this->routineRegisterExercisesRequestService->delete($request);
    }

    
}