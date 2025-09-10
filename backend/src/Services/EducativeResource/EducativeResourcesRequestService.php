<?php

namespace App\Services\EducativeResource;



use App\Request\EducativeResources\CreateEducativeResourceRequest;
use App\Request\EducativeResources\DeleteEducativeResourceRequest;
use App\Request\EducativeResources\EditEducativeResourceRequest;
use App\Request\EducativeResources\GetEducativeResourceRequest;
use App\Request\EducativeResources\ListEducativeResourcesRequest;
use App\Services\Document\DocumentService;
use App\Services\User\RoleService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class EducativeResourcesRequestService extends JWTHandlerService
{

    public function __construct(
        protected EducativeResourcesService $educativeResourceService,
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DocumentService $documentService,
        protected RoleService $roleService,
    )
    {
        parent::__construct($token, $jwtManager);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EDUCATIVE RESOURCES REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE RECURSOS EDUCATIVOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE EDUCATIVE RESOURCE
     * ES: PETICIÓN PARA OBTENER EL RECURSO EDUCATIVO
     *
     * @param GetEducativeResourceRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getEducativeResourceRequestService(GetEducativeResourceRequest $request): APIJsonResponse
    {
        $data = $this->educativeResourceService->getEducativeResourceById($request->educativeResourceId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Recurso educativo obtenido con éxito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST EDUCATIVE RESOURCES WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR RECURSOS EDUCATIVOS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListEducativeResourcesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listEducativeResourcesRequestService(ListEducativeResourcesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->educativeResourceService->listEducativeResourcesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de recursos educativos obtenido con éxito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE AN EDUCATIVE RESOURCE WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UN RECURSO EDUCATIVO CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateEducativeResourceRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // -----------------------------------------------------------
    public function createEducativeResourceRequestService(CreateEducativeResourceRequest $request): APIJsonResponse
    {

        $this->educativeResourceService->createEducativeResourceService(
            title: $request->title,
            youtubeUrl: $request->youtubeUrl,
            description: $request->description,
            isVideo: $request->isVideo,
            tag: $request->tag
        );

        return new APIJsonResponse(
            [],
            true,
            'Recurso educativo creado con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT AN EDUCATIVE RESOURCE WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UN RECURSO EDUCATIVO CON LOS DATOS PROPORCIONADOS
     *
     * @param EditEducativeResourceRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException|APIException
     */
    // ------------------------------------------------------------
    public function editEducativeResourceRequestService(EditEducativeResourceRequest $request): APIJsonResponse
    {
        $educativeResource = $this->educativeResourceService->getEducativeResourceById($request->educativeResourceId);


        if(!$educativeResource)
        {
            throw new APIException('El recurso educativo no existe', code: 404);
        }

                
        $this->educativeResourceService->editEducativeResourceService(
            educativeResource: $educativeResource,
            title: $request->title,
            youtubeUrl: $request->youtubeUrl,
            description: $request->description,
            isVideo: $request->isVideo,
            tag: $request->tag
        );

        return new APIJsonResponse(
            [],
            true,
            'Recurso educativo editado con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE AN EDUCATIVE RESOURCE
     * ES: PETICIÓN PARA ELIMINAR UN RECURSO EDUCATIVO
     *
     * @param DeleteEducativeResourceRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteEducativeResourceRequestService(DeleteEducativeResourceRequest $request): APIJsonResponse
    {
        $educativeResource = $this->educativeResourceService->getEducativeResourceById($request->educativeResourceId);

        $this->educativeResourceService->deleteEducativeResourceService($educativeResource);

        return new APIJsonResponse(
            [],
            true,
            'Recurso educativo eliminado con éxito'
        );
    }
    // -------------------------------------------------------
}