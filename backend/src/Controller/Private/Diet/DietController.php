<?php

namespace App\Controller\Private\Diet;

use App\Attribute\Permission;
use App\Request\Diet\CreateDietRequest;
use App\Request\Diet\DeleteDietRequest;
use App\Request\Diet\EditDietRequest;
use App\Request\Diet\GetDietRequest;
use App\Request\Diet\GetDietWithDaysRequest;
use App\Request\Diet\ListDietRequest;
use App\Request\Diet\ListDietFoodRequest;
use App\Request\User\AssignDietToUserRequest;
use App\Services\Diet\DietRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/diet")]
class DietController extends AbstractController
{
    public function __construct(
        protected DietRequestService $dietRequestService
    )
    {

    }
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: DIET ENDPOINTS
    // ES: ENDPOINTS DE DIETA
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A DIET
     * ES: ENDPOINT PARA OBTENER UNA DIETA
     *
     * @param GetDietRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-diet', name: 'diet_get', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function getDiet(GetDietRequest $request): Response
    {
        return $this->dietRequestService->getDietRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST DIETS
     * ES: ENDPOINT PARA LISTAR DIETAS
     *
     * @param ListDietRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-diets', name: 'diets_list', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'list')]
    public function listDiets(ListDietRequest $request): Response
    {
        return $this->dietRequestService->listDietRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE A DIET
     * ES: ENDPOINT PARA CREAR UNA DIETA
     *
     * @param CreateDietRequest $request
     * @return Response
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-diet', name: 'diet_create', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'create')]
    public function createDiet(CreateDietRequest $request): Response
    {
        return $this->dietRequestService->createDietRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT A DIET
     * ES: ENDPOINT PARA EDITAR UNA DIETA
     *
     * @param EditDietRequest $request
     * @return Response
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-diet', name: 'diet_edit', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'edit')]
    public function editDiet(EditDietRequest $request): Response
    {
        return $this->dietRequestService->editDietRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE A DIET
     * ES: ENDPOINT PARA ELIMINAR UNA DIETA
     *
     * @param DeleteDietRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-diet', name: 'diet_delete', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'delete')]
    public function deleteDiet(DeleteDietRequest $request): Response
    {
        return $this->dietRequestService->deleteDietRequestService($request);
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
     * EN: ENDPOINT TO LIST ALL FOOD ON DIET
     * ES: ENDPOINT PARA LISTAR TODOS LOS ALIMENTOS DE LA DIETA
     *
     * @param ListDietFoodRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-diet-food', name: 'diet_list_food', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'list')]
    public function listDietFood(ListDietFoodRequest $request): Response
    {
        return $this->dietRequestService->listDietFoodRequestService($request);
    }
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A DIET FORMATTED BY DAYS
     * ES: ENDPOINT PARA OBTENER UNA DIETA FORMATEADA POR DÍAS
     *
     * @param GetDietWithDaysRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-diet-with-days', name: 'diet_get_with_days', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function getDietWithDays(GetDietWithDaysRequest $request): Response
    {
        return $this->dietRequestService->getDietWithDaysRequestService($request);
    }


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A DIET FOR EDITTING
     * ES: ENDPOINT PARA OBTENER UNA DIETA PARA EDITAR
     *
     * @param GetDietWithDaysRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-diet-for-edit', name: 'diet_get_for_edit', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function getDietForEdit(GetDietWithDaysRequest $request): Response
    {
        return $this->dietRequestService->getDietForEditRequestService($request);
    }


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO ASSIGN A DIET TO A USER
     * ES: ENDPOINT PARA ASIGNAR UNA DIETA A UN USUARIO
     *
     * @param AssignDietToUserRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/assign-user', name: 'diet_assign_user', methods: ["POST"])]
    #[Permission(group: 'diets', action: 'get')]
    public function assignUserToDiet(AssignDietToUserRequest $request): Response
    {
        return $this->dietRequestService->assignDietToUserRequestService($request);
    }
    // ---------------------------------------------------------------------
}