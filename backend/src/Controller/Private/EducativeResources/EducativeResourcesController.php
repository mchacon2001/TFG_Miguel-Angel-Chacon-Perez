<?php

namespace App\Controller\Private\EducativeResources;

use App\Attribute\Permission;
use App\Request\EducativeResources\CreateEducativeResourceRequest;
use App\Request\EducativeResources\DeleteEducativeResourceRequest;
use App\Request\EducativeResources\EditEducativeResourceRequest;
use App\Request\EducativeResources\GetEducativeResourceRequest;
use App\Request\EducativeResources\ListEducativeResourcesRequest;
use App\Services\EducativeResource\EducativeResourcesRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/educative-resources", name: "educative_resources")]
class EducativeResourcesController extends AbstractController
{
    public function __construct(
        protected EducativeResourcesRequestService $educativeResourcesRequestService
    )
    {

    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EDUCATIVE RESOURCES ENDPOINTS
    // ES: ENDPOINTS DE RECURSOS EDUCATIVOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET AN EDUCATIVE RESOURCE
     * ES: ENDPOINT PARA OBTENER UN RECURSO EDUCATIVO
     *
     * @param GetEducativeResourceRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-educative-resource', name: 'educative_resource_get', methods: ["POST"])]
    #[Permission(group: 'educative_resources', action: 'get')]
    public function getEducativeResource(GetEducativeResourceRequest $request): Response
    {
        return $this->educativeResourcesRequestService->getEducativeResourceRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST EDUCATIVE RESOURCES
     * ES: ENDPOINT PARA LISTAR RECURSOS EDUCATIVOS
     *
     * @param ListEducativeResourcesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-educative-resources', name: 'educative_resources_list', methods: ["POST"])]
    #[Permission(group: 'educative_resources', action: 'list')]
    public function listEducativeResources(ListEducativeResourcesRequest $request): Response
    {
        return $this->educativeResourcesRequestService->listEducativeResourcesRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE AN EDUCATIVE RESOURCE
     * ES: ENDPOINT PARA CREAR RECURSOS EDUCATIVOS
     *
     * @param CreateEducativeResourceRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-educative-resource', name: 'educative_resource_create', methods: ["POST"])]
    #[Permission(group: 'educative_resources', action: 'create')]
    #[Permission(group: 'educative_resources', action: 'admin_educative_resources')]

    public function createEducativeResource(CreateEducativeResourceRequest $request): Response
    {
        return $this->educativeResourcesRequestService->createEducativeResourceRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT AN EDUCATIVE RESOURCE
     * ES: ENDPOINT PARA EDITAR RECURSOS EDUCATIVOS
     *
     * @param EditEducativeResourceRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-educative-resource', name: 'educative_resource_edit', methods: ["POST"])]
    #[Permission(group: 'educative_resources', action: 'edit')]
    #[Permission(group: 'educative_resources', action: 'admin_educative_resources')]
    public function editEducativeResource(EditEducativeResourceRequest $request): Response
    {
        return $this->educativeResourcesRequestService->editEducativeResourceRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE AN EDUCATIVE RESOURCE
     * ES: ENDPOINT PARA ELIMINAR UN RECURSO EDUCATIVO
     *
     * @param DeleteEducativeResourceRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-educative-resource', name: 'educative_resource_delete', methods: ["POST"])]
    #[Permission(group: 'educative_resources', action: 'delete')]
    #[Permission(group: 'educative_resources', action: 'admin_educative_resources')]
    public function deleteEducativeResource(DeleteEducativeResourceRequest $request): Response
    {
        return $this->educativeResourcesRequestService->deleteEducativeResourceRequestService($request);
    }
    // ---------------------------------------------------------------------


}