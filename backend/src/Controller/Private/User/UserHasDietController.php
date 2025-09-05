<?php

namespace App\Controller\Private\User;

use App\Attribute\Permission;
use App\Request\User\CreateUserHasDietRequest;
use App\Request\User\DeleteUserHasDietRequest;
use App\Request\User\EditUserHasDietRequest;
use App\Request\User\GetUserHasDietRequest;
use App\Request\User\ListUserHasDietRequest;
use App\Request\User\ToggleUserHasDietRequest;
use App\Services\User\UserHasDietRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/user-has-diet")]
class UserHasDietController extends AbstractController
{

    public function __construct(
        protected UserHasDietRequestService $userHasDietRequestService
    )
    {
        // EMPTY
    }

    // ------------------------------------------------------------
    /**
     * EN: END-POINT TO GET A ROLE
     * ES: END-POINT PARA OBTENER UN ROL
     *
     * @param GetUserHasDietRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/get', name: 'user_has_diet_get', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function get(GetUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->getById($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO LIST ALL THE ROLES
     * ES: END-POINT PARA LISTAR TODOS LOS ROLES
     *
     * @param ListUserHasDietRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/list', name: 'user_has_diet_list', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'list')]
    public function list(ListUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->list($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE A ROLE
     * ES: END-POINT PARA CREAR UN ROL
     *
     * @param CreateUserHasDietRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/create', name: 'user_has_diet_create', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'create')]
    public function create(CreateUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->create($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT A ROLE
     * ES: END-POINT PARA EDITAR UN ROL
     *
     * @param EditUserHasDietRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/edit', name: 'user_has_diet_edit', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'edit')]
    public function edit(EditUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->edit($request);
    }
    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO DELETE A ROLE
     * ES: END-POINT PARA ELIMINAR UN ROL
     *
     * @param DeleteUserHasDietRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/delete', name: 'user_has_diet_delete', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'delete')]
    public function delete(DeleteUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->delete($request);
    }
    // -----------------------------------------------------------------

    // -----------------------------------------------------------------
    #[Route('/toggle', name: 'user_has_diet_toggle', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'edit')]
    public function toggle(ToggleUserHasDietRequest $request): Response
    {
        return $this->userHasDietRequestService->toggle($request);
    }
    // -----------------------------------------------------------------

}