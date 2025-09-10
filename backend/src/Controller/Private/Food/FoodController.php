<?php

namespace App\Controller\Private\Food;

use App\Attribute\Permission;
use App\Request\Food\CreateFoodRequest;
use App\Request\Food\DeleteFoodRequest;
use App\Request\Food\EditFoodRequest;
use App\Request\Food\GetFoodRequest;
use App\Request\Food\ListFoodRequest;
use App\Services\Food\FoodRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/food")]
class FoodController extends AbstractController
{
    public function __construct(
        protected FoodRequestService $foodRequestService
    )
    {

    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: FOOD ENDPOINTS
    // ES: ENDPOINTS DE ALIMENTOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET A FOOD
     * ES: ENDPOINT PARA OBTENER UN ALIMENTO
     *
     * @param GetFoodRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-food', name: 'food_get', methods: ["POST"])]
    #[Permission(group: 'food', action: 'get')]
    public function getFood(GetFoodRequest $request): Response
    {
        return $this->foodRequestService->getFoodRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST FOOD
     * ES: ENDPOINT PARA LISTAR ALIMENTOS
     *
     * @param ListFoodRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-food', name: 'food_list', methods: ["POST"])]
    #[Permission(group: 'food', action: 'list')]
    public function listFood(ListFoodRequest $request): Response
    {
        return $this->foodRequestService->listFoodRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE A FOOD
     * ES: ENDPOINT PARA CREAR ALIMENTOS
     *
     * @param CreateFoodRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-food', name: 'food_create', methods: ["POST"])]
    #[Permission(group: 'food', action: 'admin_food')]
    #[Permission(group: 'food', action: 'create')]
    public function createFood(CreateFoodRequest $request): Response
    {
        return $this->foodRequestService->createFoodRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT A FOOD
     * ES: ENDPOINT PARA EDITAR ALIMENTOS
     *
     * @param EditFoodRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-food', name: 'food_edit', methods: ["POST"])]
    #[Permission(group: 'food', action: 'admin_food')]
    #[Permission(group: 'food', action: 'edit')]
    public function editFood(EditFoodRequest $request): Response
    {
        return $this->foodRequestService->editFoodRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE A FOOD
     * ES: ENDPOINT PARA ELIMINAR UN ALIMENTO
     *
     * @param DeleteFoodRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-food', name: 'food_delete', methods: ["POST"])]
    #[Permission(group: 'food', action: 'admin_food')]
    #[Permission(group: 'food', action: 'delete')]
    public function deleteFood(DeleteFoodRequest $request): Response
    {
        return $this->foodRequestService->deleteFoodRequestService($request);
    }
    // ---------------------------------------------------------------------


}