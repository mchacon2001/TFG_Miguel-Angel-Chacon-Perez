<?php

namespace App\Services\Permission;

use App\Utils\Classes\JWTHandlerService;
use App\Utils\Tools\APIJsonResponse;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class PermissionRequestService extends JWTHandlerService
{

    public function __construct(
        protected PermissionService $permissionService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager
    )
    {
        parent::__construct( $token, $jwtManager);

    }


    // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO GET ALL THE PERMISSIONS
     * ES: PETICIÓN PARA OBTENER TODOS LOS PERMISOS
     *
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function getAll(): APIJsonResponse
    {
        $data = $this->permissionService->getAvailablePermissions($this->isSuperAdmin(), true);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de permisos.'
        );
    }
    // -----------------------------------------------------------------
}