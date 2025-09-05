<?php

namespace App\Controller\Private\User;

use App\Attribute\Permission;
use App\Entity\User\User;
use App\Request\User\ChangePasswordUserRequest;
use App\Request\User\GetCalorieIntakeRequest;
use App\Request\User\GetMentalStatsRequest;
use App\Request\User\GetPhysicalStatsRequest;
use App\Request\User\ListUsersRequest;
use App\Request\User\CreateUserRequest;
use App\Request\User\DeleteUserRequest;
use App\Request\User\EditUserRequest;
use App\Request\User\GetUserRequest;
use App\Request\User\ToggleUserRequest;
use App\Request\User\EditPermissionsUserRequest;
use App\Request\User\ResetPermissionsUserRequest;
use App\Request\Role\ListRoleRequest;
use App\Request\User\CreatePhysicalStatsRequest;
use App\Request\User\CreateMentalStatsRequest;
use App\Request\User\CreateReportRequest;
use App\Services\User\UserRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/users")]
class UserController extends AbstractController
{

    protected User $user;
    public function __construct(
        protected UserRequestService $userRequestService
    )
    {

    }

    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO GET THE USER DATA
     * ES: END-POINT PARA OBTENER LOS DATOS DEL USUARIO
     *
     * @param GetUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get', name: 'user_get', methods: ["POST"])]
    #[Permission(group: 'user', action: 'get')]
    public function get(GetUserRequest $request): Response
    {
        return $this->userRequestService->get($request);
    }
    // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO LIST USERS
     * ES: END-POINT PARA LISTAR USUARIOS
     *
     * @param ListUsersRequest $request
     * @return Response
     * @throws Exception
     */
    // ---------------------------------------------------------------------
    #[Route('/list', name: 'user_list', methods: ["POST"])]
    #[Permission(group: 'user', action: 'list')]
    public function list(ListUsersRequest $request): Response
    {
        return $this->userRequestService->list($request);
    }
    // ---------------------------------------------------------------------
    
    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE A USER
     * ES: END-POINT PARA CREAR UN USUARIO
     *
     * @param CreateUserRequest $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws APIException
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    // ---------------------------------------------------------------------
    #[Route('/create', name: 'user_create', methods: ["POST"])]
    #[Permission(group: 'user', action: 'create')]
    #[Permission(group: 'user', action: 'admin_user')]
    public function create(CreateUserRequest $request, MailerInterface $mailer): Response
    {
        return $this->userRequestService->create($request, $mailer);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO EDIT A USER STATE
     * ES: END-POINT PARA EDITAR UN USUARIO
     *
     * @param EditUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit', name: 'user_edit', methods: ["POST"])]
    #[Permission(group: 'user', action: 'edit')]
    public function edit(EditUserRequest $request): Response
    {
        return $this->userRequestService->edit($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO DELETE A USER
     * ES: END-POINT PARA ELIMINAR UN USUARIO
     *
     * @param DeleteUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete', name: 'user_delete', methods: ["POST"])]
    #[Permission(group: 'user', action: 'delete')]
    #[Permission(group: 'user', action: 'admin_user')]
    public function delete(DeleteUserRequest $request): Response
    {
        return $this->userRequestService->delete($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO CHANGE THE USER PASSWORD
     * ES: END-POINT PARA CAMBIAR LA CONTRASEÑA DEL USUARIO
     *
     * @param ChangePasswordUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/change-password', name: 'user_change_password', methods: ["POST"])]
    #[Permission(group: 'user', action: 'edit')]
    public function changePassword(ChangePasswordUserRequest $request): Response
    {
        return $this->userRequestService->changePassword($request);
    }
    // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO ADD PERMISSION TO USER
     * ES: END-POINT PARA AGREGAR UN PERMISO A UN USUARIO
     *
     * @param EditPermissionsUserRequest $request
     * @return Response
     * @throws Exception
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-permissions', name: 'user_edit_permissions', methods: ["POST"])]
    #[Permission(group: 'user', action: 'admin_user')]
    #[Permission(group: 'user', action: 'edit')]
    public function editPermissions(EditPermissionsUserRequest $request): Response
    {
        return $this->userRequestService->editPermissions($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     *
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/reset-permissions', name: 'reset_user_permissions', methods: ["POST"])]
    #[Permission(group: 'user', action: 'admin_user')]
    #[Permission(group: 'user', action: 'edit')]
    public function resetPermissions(ResetPermissionsUserRequest $request): Response
    {
        return $this->userRequestService->resetPermissions($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO TOGGLE A USER STATE
     * ES: END-POINT PARA CAMBIAR EL ESTADO DE UN USUARIO
     *
     * @param ToggleUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/toggle', name: 'user_toggle', methods: ["POST"])]
    #[Permission(group: 'user', action: 'admin_user')]
    #[Permission(group: 'user', action: 'edit')]
    public function toggle(ToggleUserRequest $request): Response
    {
        return $this->userRequestService->toggle($request);
    }
    // ---------------------------------------------------------------------

        // ---------------------------------------------------------------------------------------------
    /**
     * EN: FUNCTION TO LIST ROLES TO ASSIGN TO USER
     * ES: FUNCIÓN PARA LISTAR ROLES PARA ASIGNAR A UN USUARIO
     *
     * @param ListRoleRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------------------------------
    #[Route('/list-user-roles', name: 'user_list_roles', methods: ["POST"])]
    #[Permission(group: 'user', action: 'admin_user')]
    #[Permission(group: 'user', action: 'create')]
    public function listRoles(ListRoleRequest $request): Response
    {
        return $this->userRequestService->listRoles($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------

    /**
     * EN: END-POINT TO CREATE PHYSICAL ACTIVITY FOR USER
     * ES: END-POINT PARA CREAR UNA ACTIVIDAD FÍSICA PARA EL USUARIO
     *
     * @return CreatePhysicalStatsRequest
     * @throws NonUniqueResultException
     * 
     */
    // ---------------------------------------------------------------------

    #[Route('/add-physical-stats', name: 'user_add_physical_stats', methods: ["POST"])]
    #[Permission(group: 'user_progress', action: 'get_progress')]
    public function createPhysicalActivity(CreatePhysicalStatsRequest $request): Response
    {
        return $this->userRequestService->createPhysicalStats($request);
    }
    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO CREATE MENTAL STATS FOR USER
     * ES: END-POINT PARA CREAR ESTADÍSTICAS MENTALES PARA EL USUARIO
     *
     * @return CreateMentalStatsRequest
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    
    #[Route('/add-mental-stats', name: 'user_add_mental_stats', methods: ["POST"])]
    #[Permission(group: 'user_progress', action: 'get_progress')]
    public function createMentalStats(CreateMentalStatsRequest $request): Response
    {
        return $this->userRequestService->createMentalStats($request);
    }
    // ---------------------------------------------------------------------   

    // ---------------------------------------------------------------------

    /**
     * EN: END-POINT TO GET THE MENTAL STATS OF A USER
     * ES: END-POINT PARA OBTENER LAS ESTADÍSTICAS MENTALES DE UN USUARIO
     *
     * @param GetMentalStatsRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-mental-stats', name: 'user_get_mental_stats', methods: ["POST"])]
    #[Permission(group: 'user_progress', action: 'get_progress')]
    public function getMentalStats(GetMentalStatsRequest $request): Response
    {
        return $this->userRequestService->getMentalStats($request);
    }
    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO GET THE PHYSICAL STATS OF A USER
     * ES: END-POINT PARA OBTENER LAS ESTADÍSTICAS FÍSICAS DE UN USUARIO
     *
     * @param GetPhysicalStatsRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-physical-stats', name: 'user_get_physical_stats', methods: ["POST"])]
    #[Permission(group: 'user_progress', action: 'get_progress')]
    public function getPhysicalStats(GetPhysicalStatsRequest $request): Response
    {
        return $this->userRequestService->getPhysicalStats($request);
    }
    // ---------------------------------------------------------------------
    #[Route('/get-calorie-intake', name: 'user_get_calorie_intake', methods: ["POST"])]
    public function getCalorieIntake(GetCalorieIntakeRequest $request): Response
    {
        return $this->userRequestService->getCalorieIntake($request);
    }

    // ---------------------------------------------------------------------
    /**
     * EN: END-POINT TO GENERATE USER REPORT
     * ES: END-POINT PARA GENERAR INFORME DE USUARIO
     */
    #[Route('/generate-report', name: 'users_generate_report', methods: ["POST"])]
    public function generateReport(CreateReportRequest $request): Response
    {
        return $this->userRequestService->generateReport($request);
    }
    // ---------------------------------------------------------------------
}