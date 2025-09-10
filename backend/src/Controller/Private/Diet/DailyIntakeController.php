<?php

namespace App\Controller\Private\Diet;

use App\Attribute\Permission;
use App\Request\Diet\CreateDailyIntakeRequest;
use App\Request\Diet\DeleteDailyIntakeRequest;
use App\Request\Diet\EditDailyIntakeRequest;
use App\Request\Diet\GetDailyIntakeRequest;
use App\Request\Diet\ListDailyIntakeRequest;
use App\Request\User\CreateUserHasDietRequest;
use App\Request\User\DeleteUserHasDietRequest;
use App\Request\User\EditUserHasDietRequest;
use App\Request\User\GetUserHasDietRequest;
use App\Request\User\ListUserHasDietRequest;
use App\Services\Diet\DailyIntakeRequestService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/user-has-diet/daily-intake")]
class DailyIntakeController extends AbstractController
{

    public function __construct(
        protected DailyIntakeRequestService $dailyIntakeRequestService
    )
    {
        // EMPTY
    }

    // ------------------------------------------------------------
    /**
     * EN: END-POINT TO GET A ROLE
     * ES: END-POINT PARA OBTENER UN ROL
     *
     * @param GetDailyIntakeRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/get', name: 'daily_intake_get', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function get(GetDailyIntakeRequest $request): Response
    {
        return $this->dailyIntakeRequestService->getDailyIntake($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO LIST ALL THE ROLES
     * ES: END-POINT PARA LISTAR TODOS LOS ROLES
     *
     * @param ListDailyIntakeRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/list', name: 'daily_intake_list', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'list')]
    public function list(ListDailyIntakeRequest $request): Response
    {
        return $this->dailyIntakeRequestService->list($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE A ROLE
     * ES: END-POINT PARA CREAR UN ROL
     *
     * @param CreateDailyIntakeRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/create', name: 'daily_intake_create', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'create')]
    public function create(CreateDailyIntakeRequest $request): Response
    {
        return $this->dailyIntakeRequestService->createDailyIntake($request);
    }
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT A ROLE
     * ES: END-POINT PARA EDITAR UN ROL
     *
     * @param EditDailyIntakeRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/edit', name: 'daily_intake_edit', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'edit')]
    public function edit(EditDailyIntakeRequest $request): Response
    {
        return $this->dailyIntakeRequestService->edit($request);
    }
    // -----------------------------------------------------------------
    // -----------------------------------------------------------------
    /**
     * EN: END-POINT TO DELETE A ROLE
     * ES: END-POINT PARA ELIMINAR UN ROL
     *
     * @param DeleteDailyIntakeRequest $request
     * @return Response
     * @throws Exception
     */
    // -----------------------------------------------------------------
    #[Route('/delete', name: 'daily_intake_delete', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'delete')]
    public function delete(DeleteDailyIntakeRequest $request): Response
    {
        return $this->dailyIntakeRequestService->delete($request);
    }
    // -----------------------------------------------------------------
}