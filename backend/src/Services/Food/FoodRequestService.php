<?php

namespace App\Services\Food;

use App\Request\Food\CreateFoodRequest;
use App\Request\Food\DeleteFoodRequest;
use App\Request\Food\EditFoodRequest;
use App\Request\Food\GetFoodRequest;
use App\Request\Food\ListFoodRequest;
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

class FoodRequestService extends JWTHandlerService
{

    public function __construct(
        protected FoodService $foodService,
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
    // EN: FOOD REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE ALIMENTOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE FOOD ITEM
     * ES: PETICIÓN PARA OBTENER UN ALIMENTO
     *
     * @param GetFoodRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getFoodRequestService(GetFoodRequest $request): APIJsonResponse
    {
        $data = $this->foodService->getFoodById($request->foodId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Alimento obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST FOOD WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR ALIMENTOS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListFoodRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listFoodRequestService(ListFoodRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->foodService->listFoodService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de alimentos obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A FOOD ITEM WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UN ALIMENTO CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateFoodRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // -----------------------------------------------------------
    public function createFoodRequestService(CreateFoodRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());

        $this->foodService->createFoodService(
            name: $request->name,
            user: $user,
            description: $request->description,
            calories: $request->calories,
            proteins: $request->proteins,
            carbs: $request->carbs,
            fats: $request->fats,
        );

        return new APIJsonResponse(
            [],
            true,
            'Alimento creado con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT A FOOD CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UNA CATEGORÍA DE ALIMENTO CON LOS DATOS PROPORCIONADOS
     *
     * @param EditFoodRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException|APIException
     */
    // ------------------------------------------------------------
    public function editFoodRequestService(EditFoodRequest $request): APIJsonResponse
    {
        $food = $this->foodService->getFoodByIdSimple($request->foodId);

        if(!$food)
        {
            throw new APIException('El alimento no existe', code: 404);
        }
        $this->foodService->editFoodService(
            food: $food,
            name: $request->name,
            description: $request->description,
            calories: $request->calories,
            proteins: $request->proteins,
            carbs: $request->carbs,
            fats: $request->fats,
        );

        return new APIJsonResponse(
            [],
            true,
            'Alimento editado con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A FOOD ITEM
     * ES: PETICIÓN PARA ELIMINAR UN ALIMENTO
     *
     * @param DeleteFoodRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteFoodRequestService(DeleteFoodRequest $request): APIJsonResponse
    {
        $food = $this->foodService->getFoodByIdSimple($request->foodId);

        $this->foodService->deleteFoodService($food);

        return new APIJsonResponse(
            [],
            true,
            'Alimento eliminado con éxito'
        );
    }
    // -------------------------------------------------------
}