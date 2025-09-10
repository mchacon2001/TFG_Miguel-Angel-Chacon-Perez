<?php

namespace App\Services\User;


use App\Request\Role\CreateRoleRequest;
use App\Request\Role\DeleteRoleRequest;
use App\Request\Role\EditPermissionsRoleRequest;
use App\Request\Role\EditRoleRequest;
use App\Request\Role\GetRoleRequest;
use App\Request\Role\ListRoleRequest;
use App\Request\Role\ToggleRoleRequest;
use App\Utils\Classes\JWTHandlerService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Tools\FilterService;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RoleRequestService extends JWTHandlerService
{

    public function __construct(
        protected RoleService $roleService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
    )
    {
        parent::__construct( $token, $jwtManager);
    }

    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO GET A ROLE
     * ES: PETICIÓN PARA OBTENER UN ROL
     *
     * @param GetRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function get(GetRoleRequest $request): APIJsonResponse
    {
        $role = $this->roleService->getRoleById(
            roleId: $request->roleId,
            array: true
        );

        return new APIJsonResponse(
            $role,
            true,
            'Rol seleccionado'
        );
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO LIST ALL THE ROLES
     * ES: PETICIÓN PARA LISTAR TODOS LOS ROLES
     *
     * @param ListRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function list(ListRoleRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->roleService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de roles seleccionados.'
        );
    }
    // -----------------------------------------------------------------

    public function getAll(): APIJsonResponse
    {
        
            $data = $this->roleService->getAllRoles(true);
        
        

        return new APIJsonResponse(
            $data,
            true,
            'Listado de roles.'
        );
    }
    // -----------------------------------------------------------------

    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A ROLE
     * ES: PETICIÓN PARA CREAR UN ROL
     *
     * @param CreateRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    
    public function create(CreateRoleRequest $request): APIJsonResponse
    {

        $this->roleService->create(
            name: $request->name,
            description: $request->description,
            permissions: $request->permissions
        );

        return new APIJsonResponse(
            [],
            true,
            'Rol creado con éxito'
        );
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT A ROLE
     * ES: PETICIÓN PARA EDITAR UN ROL
     *
     * @param EditRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function edit(EditRoleRequest $request): APIJsonResponse
    {
        $role = $this->roleService->getRoleById($request->roleId);

        if(!$role->isImmutable())
        {
            $roleEdited = $this->roleService->edit(
                role: $role,
                name: $request->name,
                description: $request->description,
                permissionsArray: $request->permissions
            );

            return new APIJsonResponse(
                $roleEdited,
                true,
                'Rol editado con éxito'
            );
        }

        return new APIJsonResponse(
            [],
            false,
            'No se ha podido editar el Rol.'
        );
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO TOGGLE A ROLE
     * ES: PETICIÓN PARA ACTIVAR/DESACTIVAR UN ROL
     *
     * @param ToggleRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function toggle(ToggleRoleRequest $request): APIJsonResponse
    {
        $role = $this->roleService->getRoleById($request->roleId);
        $toggleStatus = ' ';

        if(!$role->isImmutable())
        {
            $toggleStatus = $this->roleService->toggle(role: $role);
        }

        return new APIJsonResponse(
            [],
            true,
            'El rol ha sido ' . $toggleStatus . ' con éxito.'
        );
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT PERMISSIONS OF A ROLE
     * ES: PETICIÓN PARA EDITAR LOS PERMISOS DE UN ROL
     *
     * @param EditPermissionsRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function editPermissions(EditPermissionsRoleRequest $request): APIJsonResponse
    {
        $role = $this->roleService->getRoleById($request->roleId);

            if($role && !$role->isImmutable() )
            {
                $role = $this->roleService->editPermissions(
                    role: $role,
                    permissions: $request->permissions);

                $data = $this->roleService->getRoleById($role->getId(), true);
                $success = true;
                $message = 'Se han editado los permisos del Rol.';
            }
            else
            {
                $data = $this->roleService->getRoleById($role->getId(), true);
                $success = false;
                $message = 'No se ha podido eliminar el Rol.';
            }


        return new APIJsonResponse(
            $data,
            $success,
            $message
        );
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A ROLE
     * ES: PETICIÓN PARA ELIMINAR UN ROL
     *
     * @param DeleteRoleRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function delete(DeleteRoleRequest $request): APIJsonResponse
    {
        $role = $this->roleService->getRoleById($request->roleId);


            if($role && !$role->isImmutable() )
            {
                $role = $this->roleService->delete(role: $role);
            }

            if($role)
            {
                $data = $this->roleService->getRoleById($role->getId(), true);
                $success = false;
                $message = 'No se ha podido eliminar el Rol';
            }
            else
            {
                $data = [];
                $success = true;
                $message = 'Rol eliminado con éxito';
            }


        return new APIJsonResponse(
            $data,
            $success,
            $message
        );
    }
    // -----------------------------------------------------------------
}