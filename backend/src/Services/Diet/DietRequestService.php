<?php

namespace App\Services\Diet;

use App\Request\Diet\CreateDietRequest;
use App\Request\Diet\DeleteDietRequest;
use App\Request\Diet\EditDietRequest;
use App\Request\Diet\GetDietRequest;
use App\Request\Diet\GetDietWithDaysRequest;
use App\Request\Diet\ListDietRequest;
use App\Request\Diet\ListDietFoodRequest;
use App\Request\User\AssignDietToUserRequest;
use App\Services\Document\DocumentService;
use App\Services\Food\FoodService;
use App\Services\User\RoleService;
use App\Services\User\UserHasDietService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DietRequestService extends JWTHandlerService
{

    public function __construct(
        protected DietService $dietService,
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DocumentService $documentService,
        protected RoleService $roleService,
        protected FoodService $foodService,
        protected UserHasDietService $userHasDietService

    )
    {
        parent::__construct($token, $jwtManager);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: DIET REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE DIETAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE DIET
     * ES: PETICIÓN PARA OBTENER LA DIETA
     *
     * @param GetDietRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getDietRequestService(GetDietRequest $request): APIJsonResponse
    {
        $data = $this->dietService->getDietById($request->dietId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Dieta obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST DIET WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR DIETAS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListDietRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listDietRequestService(ListDietRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);


        if($this->getUser()->isSuperAdmin() || $this->getUser()->isAdmin())
        {
            $filterService->addFilter('isUser', null);
        }else
        {
            $filterService->addFilter('isUser', $this->getUser()->getId());
        }

        $data = $this->dietService->listDietService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de dietas obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A DIET WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UNA DIETA CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateDietRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // -----------------------------------------------------------
public function createDietRequestService(CreateDietRequest $request): APIJsonResponse
{
    $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());

    $diet = $this->dietService->createDietService(
        name: $request->name,
        description: $request->description,
        user: $user,
        goal: $request->goal,
        toGainMuscle: $request->toGainMuscle,
        toLoseWeight: $request->toLoseWeight,
        toMaintainWeight: $request->toMaintainWeight,
        toImprovePhysicalHealth: false,
        toImproveMentalHealth: false,
        fixShoulder: false,
        fixKnees: false,
        fixBack: false,
        rehab: false,
    );

    foreach ($request->dietFood as $foodData) {
        $dayOfWeek = $foodData['day'] ?? null;
        if (!$dayOfWeek)
        {
            $this->dietService->deleteDietService($diet);
            return new APIJsonResponse(
                [],
                false,
                'Por favor, selecciona un día de la semana válido.'
            );
        }
        
        foreach ($foodData['meals'] as $meals) {
            $mealType = $meals['name'] ?? null;
            if (!$mealType) {
                $this->dietService->deleteDietService($diet);
                return new APIJsonResponse(
                    [],
                    false,
                    'Por favor, selecciona un tipo de comida válido.'
                );
            }
           foreach ($meals['foods'] as $foods){
                    $food = $this->foodService->getFoodByIdSimple($foods['foodId']);
                    if (!$food) {
                        $this->dietService->deleteDietService($diet);
                        return new APIJsonResponse(
                            [],
                            false,
                            'Por favor, selecciona un alimento válido.'
                        );
                    }
                    try {
                        $this->dietService->createDietHasFoodService(
                            diet: $diet,
                            food: $food,
                            dayOfWeek: $dayOfWeek,
                            mealType: $mealType,
                            amount: $foods['quantity'],
                        );
                    } catch (APIException $e) {
                        $this->dietService->deleteDietService($diet);
                        return new APIJsonResponse(
                            [],
                            false,
                            'Error al asociar el alimento: ' . $e->getMessage()
                        );
                    }
                }
           }
        }

        // Handle manual user assignment (if userId is provided)
        if($diet && $request->userId) {
        $user = $this->userService->getUserByIdSimple($request->userId);
        if (!$user) {
            $this->dietService->deleteDietService($diet);
            return new APIJsonResponse(
                [],
                false,
                'Por favor, selecciona un usuario valido'
            );
        }
        $this->userHasDietService->create($user, $diet);
    }

    // Auto-assign diet to users with matching flags (only if admin and flags are set)
    if ($this->getUser()->isSuperAdmin() || $this->getUser()->isAdmin()) {
        $hasAnyFlag = $request->toGainMuscle || $request->toLoseWeight || $request->toMaintainWeight;

        if ($hasAnyFlag) {
            $matchingUsers = $this->userService->getUsersByFlags(
                $request->toGainMuscle ?? false,
                $request->toLoseWeight ?? false,
                $request->toMaintainWeight ?? false,
                false,
                false,
                false,
                false,
                false,
                false
            );

            foreach ($matchingUsers as $matchingUser) {
                try {
                    // Only assign if user doesn't already have this diet
                    $existingAssignment = $this->userHasDietService->findByUserAndDiet($matchingUser->getId(), $diet->getId());
                    if (!$existingAssignment) {
                        $this->userHasDietService->create($matchingUser, $diet);
                    }
                } catch (Exception $e) {
                    error_log("Error assigning diet {$diet->getId()} to user {$matchingUser->getId()}: " . $e->getMessage());
                }
            }
        }
        // If no flags are set, don't assign to anyone automatically
    }

    return new APIJsonResponse(
        [],
        true,
        'Dieta creada con éxito.'
    );
}
// -----------------------------------------------------------

// ------------------------------------------------------------
/**
 * EN: REQUEST TO EDIT A DIET WITH THE PROVIDED DATA
 * ES: PETICIÓN PARA EDITAR UNA DIETA CON LOS DATOS PROPORCIONADOS
 *
 * @param EditDietRequest $request
 * @return APIJsonResponse
 * @throws NonUniqueResultException|APIException
 */
// ------------------------------------------------------------
public function editDietRequestService(EditDietRequest $request): APIJsonResponse
{
    $diet = $this->dietService->getDietByIdSimple($request->dietId);

    if (!$diet) {
        throw new APIException('La dieta no existe', 404);
    }

    $this->dietService->editDietService(
        diet: $diet,
        name: $request->name,
        description: $request->description,
        goal: $request->goal,
        toGainMuscle: $request->toGainMuscle,
        toLoseWeight: $request->toLoseWeight,
        toMaintainWeight: $request->toMaintainWeight,
        toImprovePhysicalHealth: false,
        toImproveMentalHealth: false,
        fixShoulder: false,
        fixKnees: false,
        fixBack: false,
        rehab: false,
    );

    foreach ($diet->getDietHasFood() as $dietFood) {
        $this->dietService->deleteDietHasFoodService($dietFood);
    }

    foreach ($request->dietFood as $dayData) {
        $day = $dayData['day'] ?? null;
        if (!$day) {
            continue;
        }
        foreach ($dayData['meals'] as $mealData) {
            $meal = $mealData['name'] ?? null;
            if (!$meal) {
                continue;
            }
            foreach ($mealData['foods'] as $foodData) {
                $foodId = $foodData['foodId'] ?? null;
                if (!$foodId || !isset($foodData['quantity'])) {
                    continue;
                }
                $food = $this->foodService->getFoodByIdSimple($foodId);
                if (!$food) {
                    continue;
                }
                try {
                    $this->dietService->createDietHasFoodService(
                        diet: $diet,
                        food: $food,
                        dayOfWeek: $day,
                        mealType: $meal,
                        amount: $foodData['quantity'],
                        notes: $foodData['notes'] ?? null
                    );
                } catch (APIException $e) {
                    return new APIJsonResponse(
                        [],
                        false,
                        'Error al asociar el alimento: ' . $e->getMessage()
                    );
                }
            }
        }
    }

    // Handle flag-based reassignment (only if admin and flags are set)
    if ($this->getUser()->isSuperAdmin() || $this->getUser()->isAdmin()) {
        // First, remove all current assignments based on flags
        $this->userHasDietService->deleteByDietId($diet->getId());

        $hasAnyFlag = $request->toGainMuscle || $request->toLoseWeight || $request->toMaintainWeight;

        if ($hasAnyFlag) {
            $matchingUsers = $this->userService->getUsersByFlags(
                $request->toGainMuscle ?? false,
                $request->toLoseWeight ?? false,
                $request->toMaintainWeight ?? false,
                false,
                false,
                false,
                false,
                false,
                false
            );

            foreach ($matchingUsers as $matchingUser) {
                try {
                    $this->userHasDietService->create($matchingUser, $diet);
                } catch (Exception $e) {
                    error_log("Error reassigning diet {$diet->getId()} to user {$matchingUser->getId()}: " . $e->getMessage());
                }
            }
        }
        // If no flags are set, don't assign to anyone
    }

    return new APIJsonResponse([], true, 'Dieta editada con éxito');
}
    // -------------------------------------------------------

    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE A DIET
     * ES: PETICIÓN PARA ELIMINAR UNA DIETA
     *
     * @param DeleteDietRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteDietRequestService(DeleteDietRequest $request): APIJsonResponse
    {
        $diet = $this->dietService->getDietByIdSimple($request->dietId);

        $this->dietService->deleteDietService($diet);

        return new APIJsonResponse(
            [],
            true,
            'Dieta eliminada con éxito'
        );
    }
    // -------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXTERNAL REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES EXTERNAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST DIET FOOD WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR ALIMENTOS DE DIETA CON LOS FILTROS SELECCIONADOS
     *
     * @param ListDietFoodRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listDietFoodRequestService(ListDietFoodRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->foodService->listFoodService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de alimentos de dieta obtenido con éxito'
        );
    }
    // -----------------------------------------------------------

    /**
     * EN: REQUEST TO GET A DIET FOR EDITING
     * ES: PETICIÓN PARA OBTENER UNA DIETA FORMATEADA PARA EDITAR
     *
     * @param GetDietWithDaysRequest $request
     * @return APIJsonResponse
     */
    public function getDietForEditRequestService(GetDietWithDaysRequest $request): APIJsonResponse
    {
        $data = $this->dietService->getDietForEdit($request->dietId);

        return new APIJsonResponse(
            $data,
            true,
            'Dieta para editar obtenida con éxito'
        );
    }

    /**
     * EN: REQUEST TO GET A DIET FORMATTED BY DAYS
     * ES: PETICIÓN PARA OBTENER UNA DIETA FORMATEADA POR DÍAS
     *
     * @param GetDietWithDaysRequest $request
     * @return APIJsonResponse
     */
    public function getDietWithDaysRequestService(GetDietWithDaysRequest $request): APIJsonResponse
    {
        $diet = $this->dietService->getDietById($request->dietId);

        if (!$diet) {
            throw new APIException('La dieta no existe', 404);
        }

        $data = $this->dietService->formatStructuredDiet($diet);

        return new APIJsonResponse(
            $data,
            true,
            'Dieta estructurada por días obtenida con éxito.'
        );
    }


    /**
     * EN: REQUEST TO ASSIGN A DIET TO A USER
     * ES: PETICIÓN PARA ASIGNAR UNA DIETA A UN USUARIO
     *
     * @param AssignDietToUserRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    public function assignDietToUserRequestService(AssignDietToUserRequest $request): APIJsonResponse
    {
        $diet = $this->dietService->getDietByIdSimple($request->dietId);

        $this->userHasDietService->deleteByDietId($diet->getId());

        foreach ($request->userIds as $userId) {
            $user = $this->userService->getUserByIdSimple($userId);
            if (!$user) {
                throw new APIException('Usuario no encontrado', code: 404);
            }

             if( $diet && $user) {
            $this->userHasDietService->create($user, $diet);
            } else {
                throw new APIException('Dieta o usuario no encontrado', code: 404);
            }
        }
       

        return new APIJsonResponse(
            [],
            true,
            'Rutina asignada al usuario con éxito'
        );
    }
    // -----------------------------------------------------------


}