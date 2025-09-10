<?php

namespace App\Services\User;

use App\Request\User\CreateUserHasDietRequest;
use App\Request\User\DeleteUserHasDietRequest;
use App\Request\User\EditUserHasDietRequest;
use App\Request\User\GetUserHasDietRequest;
use App\Request\User\ListUserHasDietRequest;
use App\Request\User\ToggleUserHasDietRequest;
use App\Services\Diet\DietService;
use App\Services\User\UserHasDietService;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;


class UserHasDietRequestService extends JWTHandlerService
{

    public function __construct(
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected UserHasDietService $userHasDietService,
        protected DietService $dietService
    )
    {
        parent::__construct( $token, $jwtManager);

    }


    // -----------------------------------------------------------------
    /**
     * @throws NonUniqueResultException
     * @throws Exception
     */
    // -----------------------------------------------------------------
    public function create(CreateUserHasDietRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserById($request->userId);
        $diet = $this->dietService->getDietById($request->dietId);

        $this->userHasDietService->create(
            $user, 
            $diet
        );

        return new APIJsonResponse(
            [],
            true,
            'Rol seleccionado'
        );   
    }


    // -----------------------------------------------------------------


    public function edit(EditUserHasDietRequest $request): APIJsonResponse
    {
        $userHasDiet = $this->userHasDietService->getById($request->userHasDietId);
        $user = $this->userService->getUserById($request->userId);
        $diet = $this->dietService->getDietById($request->dietId);

        $this->userHasDietService->edit(
            $userHasDiet,
            $user,
            $diet
        );

        return new APIJsonResponse(
            [],
            true,
            'Rutina del usuario editada correctamente'
        );
    }


    // -----------------------------------------------------------------



    public function delete(DeleteUserHasDietRequest $request): APIJsonResponse
    {

        $userHasDiet = $this->userHasDietService->getById($request->userHasDietId);

        $this->userHasDietService->remove($userHasDiet);

        return new APIJsonResponse(
            [],
            true,
            'Rutina del usuario eliminada correctamente'
        );
    }
    // -----------------------------------------------------------------

    public function list(ListUserHasDietRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->userHasDietService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de rutinas del usuario.'
        );
    }
    // -----------------------------------------------------------------


    public function getById(GetUserHasDietRequest $request): APIJsonResponse
    {
        $userHasDiet = $this->userHasDietService->getById($request->userHasDietId);

        return new APIJsonResponse(
            $userHasDiet,
            true,
            'Rutina del usuario obtenida correctamente'
        );
    }


        // -----------------------------------------------------------------
    /**
     * EN: REQUEST TO TOGGLE A ROLE
     * ES: PETICIÓN PARA ACTIVAR/DESACTIVAR UN ROL
     *
     * @param ToggleUserHasDietRequest $request
     * @return APIJsonResponse
     * @throws Exception
     */
    // -----------------------------------------------------------------
public function toggle(ToggleUserHasDietRequest $request): APIJsonResponse
{
    $userHasDiet = $this->userHasDietService->getById($request->userHasDietId);
    $userId = $userHasDiet->getUser()->getId();

    if ($userHasDiet->isSelectedDiet()) {
        $userHasDiet->setSelectedDiet(false);
        $this->userHasDietService->edit($userHasDiet, $userHasDiet->getUser(), $userHasDiet->getDiet());
    } else {
        $this->userHasDietService->deactivateAllUserDiets($userId);
        
        // Luego activar esta dieta
        $userHasDiet->setSelectedDiet(true);
        $this->userHasDietService->edit($userHasDiet, $userHasDiet->getUser(), $userHasDiet->getDiet());
    }

    return new APIJsonResponse(
        [],
        true,
        'Estado de la dieta actualizado correctamente.'
    );
}
}
