<?php

namespace App\Controller\Private\User;

use App\Attribute\Permission;
use App\Request\User\CreateUserHasRoutineRequest;
use App\Request\User\DeleteUserHasRoutineRequest;
use App\Request\User\EditUserHasRoutineRequest;
use App\Request\User\GetUserHasRoutineRequest;
use App\Request\User\ListUserHasRoutineRequest;
use App\Services\User\UserHasRoutineRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/user-has-routines")]
class UserHasRoutinesController extends AbstractController
{

    public function __construct(
        protected UserHasRoutineRequestService $userHasRoutineRequestService
    )
    {
        // EMPTY
    }

    // ------------------------------------------------------------
    /**
     * EN: END-POINT TO GET A ROLE
     * ES: END-POINT PARA OBTENER UN ROL
     *
     * @param GetUserHasRoutineRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/get', name: 'user_has_routine_get', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function get(GetUserHasRoutineRequest $request): Response
    {
        return $this->userHasRoutineRequestService->getById($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO LIST ALL THE ROLES
     * ES: END-POINT PARA LISTAR TODOS LOS ROLES
     *
     * @param ListUserHasRoutineRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/list', name: 'user_has_routine_list', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function list(ListUserHasRoutineRequest $request): Response
    {
        return $this->userHasRoutineRequestService->list($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE A ROLE
     * ES: END-POINT PARA CREAR UN ROL
     *
     * @param CreateUserHasRoutineRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/create', name: 'user_has_routine_create', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'create')]
    public function create(CreateUserHasRoutineRequest $request): Response
    {
        return $this->userHasRoutineRequestService->create($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT A ROLE
     * ES: END-POINT PARA EDITAR UN ROL
     *
     * @param EditUserHasRoutineRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/edit', name: 'user_has_routine_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'edit')]
    public function edit(EditUserHasRoutineRequest $request): Response
    {
        return $this->userHasRoutineRequestService->edit($request);
    }
    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO DELETE A ROLE
     * ES: END-POINT PARA ELIMINAR UN ROL
     *
     * @param DeleteUserHasRoutineRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/delete', name: 'user_has_routine_delete', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'delete')]
    public function delete(DeleteUserHasRoutineRequest $request): Response
    {
        return $this->userHasRoutineRequestService->delete($request);
    }
    // -----------------------------------------------------------------
}