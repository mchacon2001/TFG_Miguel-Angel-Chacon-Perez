<?php

namespace App\Controller\Private\Routine;

use App\Attribute\Permission;
use App\Request\Routine\CreateRoutineCategoryRequest;
use App\Request\Routine\CreateRoutineRequest;
use App\Request\Routine\DeleteRoutineCategoryRequest;
use App\Request\Routine\DeleteRoutineRequest;
use App\Request\Routine\EditRoutineCategoryRequest;
use App\Request\Routine\EditRoutineRequest;
use App\Request\Routine\GetRoutineCategoryRequest;
use App\Request\Routine\GetRoutineRequest;
use App\Request\Routine\GetRoutineWithDaysRequest;
use App\Request\Routine\ListRoutineCategoriesRequest;
use App\Request\Routine\ListRoutinesRequest;
use App\Request\Routine\ListRoutineExercisesRequest;
use App\Request\Routine\ToggleRoutineCategoryRequest;
use App\Request\Routine\ToggleRoutineRequest;
use App\Request\User\AssignRoutineToUserRequest;
use App\Services\Routine\RoutineRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/routines")]
class RoutineController extends AbstractController
{
    public function __construct(
        protected RoutineRequestService $routineRequestService
    )
    {

    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: ROUTINE CATEGORY ENDPOINTS
    // ES: ENDPOINTS DE CATEGORÍA DE RUTINAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A ROUTINE CATEGORY
     * ES: ENDPOINT PARA OBTENER UNA CATEGORÍA DE RUTINA
     *
     * @param GetRoutineCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-routine-category', name: 'routine_category_get', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function getRoutineCategory(GetRoutineCategoryRequest $request): Response
    {
        return $this->routineRequestService->getRoutineCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST ROUTINE CATEGORIES
     * ES: ENDPOINT PARA LISTAR CATEGORÍAS DE RUTINA
     *
     * @param ListRoutineCategoriesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-routine-categories', name: 'routine_categories_list', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function listRoutineCategories(ListRoutineCategoriesRequest $request): Response
    {
        return $this->routineRequestService->listRoutineCategoriesRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE A ROUTINE CATEGORY
     * ES: ENDPOINT PARA CREAR UNA CATEGORÍA DE RUTINA
     *
     * @param CreateRoutineCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-routine-category', name: 'routine_category_create', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'create')]
    #[Permission(group: 'routines', action: 'admin_routines')]
    public function createRoutineCategory(CreateRoutineCategoryRequest $request): Response
    {
        return $this->routineRequestService->createRoutineCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT A ROUTINE CATEGORY
     * ES: ENDPOINT PARA EDITAR UNA CATEGORÍA DE RUTINA
     *
     * @param EditRoutineCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-routine-category', name: 'routine_category_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'admin_routines')]
    #[Permission(group: 'routines', action: 'edit')]
    public function editRoutineCategory(EditRoutineCategoryRequest $request): Response
    {
        return $this->routineRequestService->editRoutineCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE A ROUTINE CATEGORY
     * ES: ENDPOINT PARA ELIMINAR UNA CATEGORÍA DE RUTINA
     *
     * @param DeleteRoutineCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-routine-category', name: 'routine_category_delete', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'admin_routines')]
    #[Permission(group: 'routines', action: 'delete')]
    public function deleteRoutineCategory(DeleteRoutineCategoryRequest $request): Response
    {
        return $this->routineRequestService->deleteRoutineCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO TOGGLE A ROUTINE CATEGORY
     * ES: ENDPOINT PARA TOGGLEAR UNA CATEGORÍA DE RUTINA
     *
     * @param ToggleRoutineCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/toggle-routine-category', name: 'routine_category_toggle', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'admin_routines')]
    #[Permission(group: 'routines', action: 'edit')]
    public function toggleRoutineCategory(ToggleRoutineCategoryRequest $request): Response
    {
        return $this->routineRequestService->toggleRoutineCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: ROUTINE ENDPOINTS
    // ES: ENDPOINTS DE RUTINAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A ROUTINE
     * ES: ENDPOINT PARA OBTENER UNA RUTINA
     *
     * @param GetRoutineRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-routine', name: 'routine_get', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function getRoutine(GetRoutineRequest $request): Response
    {
        return $this->routineRequestService->getRoutineRequestService($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST ROUTINES
     * ES: ENDPOINT PARA LISTAR RUTINAS
     *
     * @param ListRoutinesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-routines', name: 'routines_list', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function listRoutines(ListRoutinesRequest $request): Response
    {
        return $this->routineRequestService->listRoutinesRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE A ROUTINE
     * ES: ENDPOINT PARA CREAR UNA RUTINA
     *
     * @param CreateRoutineRequest $request
     * @return Response
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-routine', name: 'routine_create', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'create')]
    public function createRoutine(CreateRoutineRequest $request): Response
    {
        return $this->routineRequestService->createRoutineRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT A ROUTINE
     * ES: ENDPOINT PARA EDITAR UNA RUTINA
     *
     * @param EditRoutineRequest $request
     * @return Response
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-routine', name: 'routine_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'edit')]
    public function editRoutine(EditRoutineRequest $request): Response
    {
        return $this->routineRequestService->editRoutineRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE A ROUTINE
     * ES: ENDPOINT PARA ELIMINAR UNA RUTINA
     *
     * @param DeleteRoutineRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-routine', name: 'routine_delete', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'delete')]
    public function deleteRoutine(DeleteRoutineRequest $request): Response
    {
        return $this->routineRequestService->deleteRoutineRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO TOGGLE A ROUTINE
     * ES: ENDPOINT PARA TOGGLEAR UNA RUTINA
     *
     * @param ToggleRoutineRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/toggle-routine', name: 'routine_toggle', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'admin_routines')]
    #[Permission(group: 'routines', action: 'edit')]
    public function toggleRoutine(ToggleRoutineRequest $request): Response
    {
        return $this->routineRequestService->toggleRoutineRequestService($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXTERNAL ENDPOINTS
    // ES: ENDPOINTS EXTERNOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST ALL EXERCISES ON ROUTINE
     * ES: ENDPOINT PARA LISTAR TODOS LOS EJERCICIOS DE LA RUTINA
     *
     * @param ListRoutineExercisesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-routine-exercises', name: 'routines_list_exercises', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'list')]
    public function listRoutineExercises(ListRoutineExercisesRequest $request): Response
    {
        return $this->routineRequestService->listRoutineExercisesRequestService($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A ROUTINE FORMATTED BY DAYS
     * ES: ENDPOINT PARA OBTENER UNA RUTINA FORMATEADA POR DÍAS
     *
     * @param GetRoutineWithDaysRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-routine-with-days', name: 'routine_get_with_days', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function getRoutineWithDays(GetRoutineWithDaysRequest $request): Response
    {
        return $this->routineRequestService->getRoutineWithDaysRequestService($request);
    }


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A ROUTINE FOR EDITTING
     * ES: ENDPOINT PARA OBTENER UNA RUTINA PARA EDITAR
     *
     * @param GetRoutineWithDaysRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-routine-for-edit', name: 'routine_get_for_edit', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function getRoutineForEdit(GetRoutineWithDaysRequest $request): Response
    {
        return $this->routineRequestService->getRoutineForEditRequestService($request);
    }


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO ASSIGN A ROUTINE TO A USER
     * ES: ENDPOINT PARA ASIGNAR UNA RUTINA A UN USUARIO
     *
     * @param AssignRoutineToUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/assign-user', name: 'routine_assign_user', methods: ["POST"])]
    #[Permission(group: 'routines', action: 'get')]
    public function assignUserToRoutine(AssignRoutineToUserRequest $request): Response
    {
        return $this->routineRequestService->assignRoutineToUserRequestService($request);
    }
    // ---------------------------------------------------------------------
}