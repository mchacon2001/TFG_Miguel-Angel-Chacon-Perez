<?php

namespace App\Services\User;

use App\Request\User\CreateUserHasRoutineRequest;
use App\Request\User\DeleteUserHasRoutineRequest;
use App\Request\User\EditUserHasRoutineRequest;
use App\Request\User\GetUserHasRoutineRequest;
use App\Request\User\ListUserHasRoutineRequest;
use App\Services\Routine\RoutineService;
use App\Services\User\UserHasRoutineService;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;


class UserHasRoutineRequestService extends JWTHandlerService
{

    public function __construct(
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected UserHasRoutineService $userHasRoutineService,
        protected RoutineService $routineService
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
    public function create(CreateUserHasRoutineRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserById($request->userId);
        $routine = $this->routineService->getRoutineById($request->routineId);

        $this->userHasRoutineService->create(
            $user, 
            $routine
        );

        return new APIJsonResponse(
            [],
            true,
            'Rol seleccionado'
        );   
    }


    // -----------------------------------------------------------------


    public function edit(EditUserHasRoutineRequest $request): APIJsonResponse
    {
        $userHasRoutine = $this->userHasRoutineService->getById($request->userHasRoutineId);
        $user = $this->userService->getUserById($request->userId);
        $routine = $this->routineService->getRoutineById($request->routineId);

        $this->userHasRoutineService->edit(
            $userHasRoutine,
            $user,
            $routine
        );

        return new APIJsonResponse(
            [],
            true,
            'Rutina del usuario editada correctamente'
        );
    }


    // -----------------------------------------------------------------



    public function delete(DeleteUserHasRoutineRequest $request): APIJsonResponse
    {

        $userHasRoutine = $this->userHasRoutineService->getById($request->userHasRoutineId);

        $this->userHasRoutineService->remove($userHasRoutine);

        return new APIJsonResponse(
            [],
            true,
            'Rutina del usuario eliminada correctamente'
        );
    }
    // -----------------------------------------------------------------

    public function list(ListUserHasRoutineRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->userHasRoutineService->list($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de rutinas del usuario.'
        );
    }
    // -----------------------------------------------------------------


    public function getById(GetUserHasRoutineRequest $request): APIJsonResponse
    {
        $userHasRoutine = $this->userHasRoutineService->getById($request->userHasRoutineId);

        return new APIJsonResponse(
            $userHasRoutine,
            true,
            'Rutina del usuario obtenida correctamente'
        );
    }


}
