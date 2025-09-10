<?php

namespace App\Controller\Private\User;

use App\Attribute\Permission;
use App\Request\Role\CreateRoleRequest;
use App\Request\Role\DeleteRoleRequest;
use App\Request\Role\EditPermissionsRoleRequest;
use App\Request\Role\EditRoleRequest;
use App\Request\Role\GetRoleRequest;
use App\Request\Role\ListRoleRequest;
use App\Request\Role\ToggleRoleRequest;
use App\Services\User\RoleRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/roles")]
class RoleController extends AbstractController
{

    public function __construct(
        protected RoleRequestService $roleRequestService
    )
    {
        // EMPTY
    }

    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO GET A ROLE
     * ES: END-POINT PARA OBTENER UN ROL
     *
     * @param GetRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/get', name: 'role_get', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'get')]
    public function get(GetRoleRequest $request): Response
    {
        return $this->roleRequestService->get($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO GET ALL THE ROLES
     * ES: END-POINT PARA OBTENER TODOS LOS ROLES
     *
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route("/get-all", name: "role_get_all", methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'get')]
    public function getAll(): Response
    {
        return $this->roleRequestService->getAll();
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO LIST ALL THE ROLES
     * ES: END-POINT PARA LISTAR TODOS LOS ROLES
     *
     * @param ListRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/list', name: 'role_list', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'list')]
    public function list(ListRoleRequest $request): Response
    {
        return $this->roleRequestService->list($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE A ROLE
     * ES: END-POINT PARA CREAR UN ROL
     *
     * @param CreateRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/create', name: 'role_create', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'create')]
    public function create(CreateRoleRequest $request): Response
    {
        return $this->roleRequestService->create($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT A ROLE
     * ES: END-POINT PARA EDITAR UN ROL
     *
     * @param EditRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/edit', name: 'role_edit', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'edit')]
    public function edit(EditRoleRequest $request): Response
    {
        return $this->roleRequestService->edit($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT THE PERMISSIONS OF A ROLE
     * ES: END-POINT PARA EDITAR LOS PERMISOS DE UN ROL
     *
     * @param EditPermissionsRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/edit-permissions', name: 'role_edit_permissions', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'edit')]
    public function editPermissions(EditPermissionsRoleRequest $request): Response
    {
        return $this->roleRequestService->editPermissions($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO TOGGLE A ROLE
     * ES: END-POINT PARA ACTIVAR/DESACTIVAR UN ROL
     *
     * @param ToggleRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/toggle', name: 'role_toggle', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'edit')]
    public function toggle(ToggleRoleRequest $request): Response
    {
        return $this->roleRequestService->toggle($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO DELETE A ROLE
     * ES: END-POINT PARA ELIMINAR UN ROL
     *
     * @param DeleteRoleRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/delete', name: 'role_delete', methods: ["POST"])]
    #[Permission(group: 'roles', action: 'admin_roles')]
    #[Permission(group: 'roles', action: 'delete')]
    public function delete(DeleteRoleRequest $request): Response
    {
        return $this->roleRequestService->delete($request);
    }
    // -----------------------------------------------------------------
}