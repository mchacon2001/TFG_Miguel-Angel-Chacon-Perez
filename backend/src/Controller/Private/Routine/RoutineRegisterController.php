<?php

namespace App\Controller\Private\Routine;

use App\Attribute\Permission;
use App\Request\Routine\CreateRoutineRegisterRequest;
use App\Request\Routine\DeleteRoutineRegisterRequest;
use App\Request\Routine\EditRoutineRegisterRequest;
use App\Request\Routine\FinishRoutineRegisterRequest;
use App\Request\Routine\GetRoutineRegisterRequest;
use App\Request\Routine\ListRoutineRegisterRequest;
use App\Request\Routine\GetActiveRoutineRegisterByUserAndRoutineRequest;
use App\Services\Routine\RoutineRegisterRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/routine-register")]
class RoutineRegisterController extends AbstractController
{

    public function __construct(
        protected RoutineRegisterRequestService $routineRegisterRequestService
    )
    {
    }

    /**
     * EN: END-POINT TO GET A ROUTINE REGISTER
     * ES: END-POINT PARA OBTENER UN REGISTRO DE RUTINA
     */

    #[Route('/get', name: 'routine_register_get', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function get(GetRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->getById($request);
    }

    /**
     * EN: END-POINT TO LIST ALL ROUTINE REGISTERS
     * ES: END-POINT PARA LISTAR TODOS LOS REGISTROS DE RUTINA
     */

    #[Route('/list', name: 'routine_register_list', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function list(ListRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->list($request);
    }

    /**
     * EN: END-POINT TO CREATE A ROUTINE REGISTER
     * ES: END-POINT PARA CREAR UN REGISTRO DE RUTINA
     */

    #[Route('/create', name: 'routine_register_create', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'create')]
    public function create(CreateRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->create($request);
    }

    /**
     * EN: END-POINT TO EDIT A ROUTINE REGISTER
     * ES: END-POINT PARA EDITAR UN REGISTRO DE RUTINA
     */

    #[Route('/edit', name: 'routine_register_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'edit')]
    public function edit(EditRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->edit($request);
    }

    /**
     * EN: END-POINT TO DELETE A ROUTINE REGISTER
     * ES: END-POINT PARA ELIMINAR UN REGISTRO DE RUTINA
     */
    
    #[Route('/delete', name: 'routine_register_delete', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'delete')]
    public function delete(DeleteRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->delete($request);
    }

    /**
    * EN: END-POINT TO FINISH A ROUTINE REGISTER
    * ES: END-POINT PARA FINALIZAR UN REGISTRO DE RUTINA
    */
    #[Route('/finish', name: 'routine_register_exercises_finish', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function finish(FinishRoutineRegisterRequest $request): Response
    {
        return $this->routineRegisterRequestService->finish($request);
    }

    /**
     * EN: END-POINT TO GET ACTIVE ROUTINE BY USER
     * ES: END-POINT PARA OBTENER LA RUTINA ACTIVA POR USUARIO
     */
    #[Route('/get-active-routine-by-user', name: 'routine_register_get_active_routine_by_user', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function getActiveRoutineByUser(GetActiveRoutineRegisterByUserAndRoutineRequest $request): Response
    {
        return $this->routineRegisterRequestService->getActiveRoutineRegisterByUserAndRoutine($request);
    }

}