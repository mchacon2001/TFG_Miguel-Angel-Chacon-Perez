<?php

namespace App\Services\Diet;


use App\Request\Diet\CreateDailyIntakeRequest;
use App\Request\Diet\DeleteDailyIntakeRequest;
use App\Request\Diet\EditDailyIntakeRequest;
use App\Request\Diet\GetDailyIntakeRequest;
use App\Request\Diet\ListDailyIntakeRequest;
use App\Services\Diet\DailyIntakeService;
use App\Services\Food\FoodService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;


class DailyIntakeRequestService extends JWTHandlerService
{

    public function __construct(
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DailyIntakeService $dailyIntakeService,
        protected DietService $dietService,
        protected FoodService $foodService
    )
    {
        parent::__construct( $token, $jwtManager);

    }

/**
 * EN: REQUEST TO CREATE A DAILY INTAKE WITH THE PROVIDED DATA
 * ES: PETICIÓN PARA CREAR UNA INGESTA DIARIA CON LOS DATOS PROPORCIONADOS
 *
 * @param CreateDailyIntakeRequest $request
 * @return APIJsonResponse
 * @throws NonUniqueResultException
 * @throws APIException
 */

public function createDailyIntake(CreateDailyIntakeRequest $request): APIJsonResponse
{
    $user = $this->userService->getUserById($request->userId);
    
    if (!$user) {
        return new APIJsonResponse(
            [],
            false,
            'Usuario no encontrado'
        );
    }


    // Obtener la fecha actual (hoy)
    $today = new DateTime('today');
    
    try {
        // Verificar si existen registros previos para el usuario en el día actual
        $existingIntakes = $this->dailyIntakeService->getByUserAndDate($user, $today);
        
        if (!empty($existingIntakes)) {
            // Si existen registros, eliminarlos antes de crear los nuevos
            $this->dailyIntakeService->deleteByUserAndDate($user, $today);
        }
    } catch (Exception $e) {
        return new APIJsonResponse(
            [],
            false,
            'Error al verificar registros existentes: ' . $e->getMessage()
        );
    }

    // Procesar cada comida
    foreach ($request->meals as $mealData) {
        $mealType = $mealData['mealType'] ?? null;
        
        if (!$mealType) {
            return new APIJsonResponse(
                [],
                false,
                'Tipo de comida no especificado'
            );
        }

        // Procesar cada alimento de la comida
        $foods = $mealData['foods'] ?? [];
        foreach ($foods as $foodData) {
            $foodId = $foodData['foodId'] ?? null;
            $quantity = $foodData['quantity'] ?? 0;

            if (!$foodId || $quantity <= 0) {
                continue; // Saltar alimentos sin ID o cantidad
            }

            $food = $this->foodService->getFoodByIdSimple($foodId);
            if (!$food) {
                
                return new APIJsonResponse(
                    [],
                    false,
                    'Alimento no encontrado: ' . $foodId
                );
            }

            try {
                $this->dailyIntakeService->create(
                    user: $user,
                    food: $food,
                    mealType: $mealType,
                    quantity: $quantity
                );
            } catch (Exception $e) {
                return new APIJsonResponse(
                    [],
                    false,
                    'Error al registrar el alimento: ' . $e->getMessage()
                );
            }
        }
    }

    return new APIJsonResponse(
        [],
        true,
        'Ingesta diaria registrada con éxito'
    );
}


    // -----------------------------------------------------------------

    public function delete(DeleteDailyIntakeRequest $request): APIJsonResponse
    {

        $dailyIntake = $this->dailyIntakeService->getById($request->dailyIntakeId);

        $this->dailyIntakeService->remove($dailyIntake);

        return new APIJsonResponse(
            [],
            true,
            'Rutina del usuario eliminada correctamente'
        );
    }
    // -----------------------------------------------------------------

    public function list(ListDailyIntakeRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->dailyIntakeService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de rutinas del usuario.'
        );
    }
    // -----------------------------------------------------------------


    /**
     * EN: REQUEST TO GET DAILY INTAKE BY USER AND DATE
     * ES: PETICIÓN PARA OBTENER INGESTA DIARIA POR USUARIO Y FECHA
     *
     * @param GetDailyIntakeRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    public function getDailyIntake(GetDailyIntakeRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserById($request->userId);
        
        if (!$user) {
            return new APIJsonResponse(
                [],
                false,
                'Usuario no encontrado'
            );
        }

        try {
            $date = new DateTime($request->date);
        } catch (Exception $e) {
            return new APIJsonResponse(
                [],
                false,
                'Fecha inválida'
            );
        }

        $dailyIntakes = $this->dailyIntakeService->getByUserAndDate($user, $date, true);

        // Organizar por tipo de comida
        $organizedIntakes = [
            'breakfast' => [],
            'midMorningSnack' => [],
            'lunch' => [],
            'afternoonSnack' => [],
            'dinner' => []
        ];

        foreach ($dailyIntakes as $intake) {
            $mealType = $intake['mealType'];
            if (isset($organizedIntakes[$mealType])) {
                $organizedIntakes[$mealType][] = [
                    'id' => $intake['id'],
                    'food' => $intake['food'],
                    'quantity' => $intake['amount'],
                    'createdAt' => $intake['createdAt']
                ];
            }
        }

        return new APIJsonResponse(
            [
                'date' => $request->date,
                'userId' => $request->userId,
                'meals' => $organizedIntakes,
                'totalIntakes' => count($dailyIntakes)
            ],
            true,
            'Ingesta diaria obtenida correctamente'
        );
    }


}
