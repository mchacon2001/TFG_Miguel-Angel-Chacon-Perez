<?php

namespace App\Controller\Private\Permission;

use App\Services\Permission\PermissionRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/permissions")]
class PermissionController extends AbstractController
{

    public function __construct(
        protected PermissionRequestService $permissionRequestService
    )
    {
        // EMPTY
    }

    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO GET ALL THE PERMISSIONS
     * ES: END-POINT PARA OBTENER TODOS LOS PERMISOS
     *
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route("/get-all", name: "permissions_get_all", methods: ["POST"])]
    public function getAll(): Response
    {
        return $this->permissionRequestService->getAll();
    }
    // -----------------------------------------------------------------
}