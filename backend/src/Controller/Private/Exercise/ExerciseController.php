<?php

namespace App\Controller\Private\Exercise;

use App\Attribute\Permission;
use App\Request\Exercise\CreateExerciseCategoryRequest;
use App\Request\Exercise\CreateExerciseRequest;
use App\Request\Exercise\DeleteExerciseCategoryRequest;
use App\Request\Exercise\DeleteExerciseRequest;
use App\Request\Exercise\EditExerciseCategoryRequest;
use App\Request\Exercise\EditExerciseRequest;
use App\Request\Exercise\GetExerciseCategoryRequest;
use App\Request\Exercise\GetExerciseRequest;
use App\Request\Exercise\ListExerciseCategoriesRequest;
use App\Request\Exercise\ListExercisesRequest;
use App\Services\Exercise\ExerciseRequestService;
use App\Utils\Exceptions\APIException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/exercises")]
class ExerciseController extends AbstractController
{
    public function __construct(
        protected ExerciseRequestService $exerciseRequestService
    )
    {

    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE CATEGORIES ENDPOINTS
    // ES: ENDPOINTS DE CATEGORÍAS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET THE EXERCISE CATEGORY
     * ES: ENDPOINT PARA OBTENER LA CATEGORÍA DE EJERCICIO
     *
     * @param GetExerciseCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-category', name: 'exercise_category_get', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'get')]
    public function getExerciseCategory(GetExerciseCategoryRequest $request): Response
    {
        return $this->exerciseRequestService->getCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST EXERCISE CATEGORIES
     * ES: ENDPOINT PARA LISTAR CATEGORÍAS DE EJERCICIOS
     *
     * @param ListExerciseCategoriesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-exercise-categories', name: 'exercise_category_list', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'list')]
    public function listExerciseCategories(ListExerciseCategoriesRequest $request): Response
    {
        return $this->exerciseRequestService->listCategoriesRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE AN EXERCISE CATEGORY
     * ES: ENDPOINT PARA CREAR UNA CATEGORÍA DE EJERCICIOS
     *
     * @param CreateExerciseCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-category', name: 'exercise_category_create', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'admin_exercises')]
    #[Permission(group: 'exercises', action: 'create')]
    public function createExerciseCategory(CreateExerciseCategoryRequest $request): Response
    {
        return $this->exerciseRequestService->createCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT AN EXERCISE CATEGORY
     * ES: ENDPOINT PARA EDITAR UNA CATEGORÍA DE EJERCICIOS
     *
     * @param EditExerciseCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-category', name: 'exercise_category_edit', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'admin_exercises')]
    #[Permission(group: 'exercises', action: 'edit')]
    public function editExerciseCategory(EditExerciseCategoryRequest $request): Response
    {
        return $this->exerciseRequestService->editCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE AN EXERCISE CATEGORY
     * ES: ENDPOINT PARA ELIMINAR UNA CATEGORÍA DE EJERCICIOS
     *
     * @param DeleteExerciseCategoryRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-category', name: 'exercise_category_delete', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'admin_exercises')]
    #[Permission(group: 'exercises', action: 'delete')]
    public function deleteExerciseCategory(DeleteExerciseCategoryRequest $request): Response
    {
        return $this->exerciseRequestService->deleteCategoryRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE ENDPOINTS
    // ES: ENDPOINTS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO GET AN EXERCISE
     * ES: ENDPOINT PARA OBTENER UN EJERCICIOS
     *
     * @param GetExerciseRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/get-exercise', name: 'exercise_get', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'get')]
    public function getExercise(GetExerciseRequest $request): Response
    {
        return $this->exerciseRequestService->getExerciseRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO LIST EXERCISE
     * ES: ENDPOINT PARA LISTAR EJERCICIOS
     *
     * @param ListExercisesRequest $request
     * @return Response
     */
    // ---------------------------------------------------------------------
    #[Route('/list-exercises', name: 'exercises_list', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'list')]
    public function listExercises(ListExercisesRequest $request): Response
    {
        return $this->exerciseRequestService->listExercisesRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO CREATE AN EXERCISE 
     * ES: ENDPOINT PARA CREAR EJERCICIOS
     *
     * @param CreateExerciseRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/create-exercise', name: 'exercise_create', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'create')]
    public function createExercise(CreateExerciseRequest $request): Response
    {
        return $this->exerciseRequestService->createExerciseRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO EDIT AN EXERCISE 
     * ES: ENDPOINT PARA EDITAR EJERCICIOS
     *
     * @param EditExerciseRequest $request
     * @return Response
     * @throws NonUniqueResultException|APIException
     */
    // ---------------------------------------------------------------------
    #[Route('/edit-exercise', name: 'exercise_edit', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'edit')]
    public function editExercise(EditExerciseRequest $request): Response
    {
        return $this->exerciseRequestService->editExerciseRequestService($request);
    }
    // ---------------------------------------------------------------------


    // ---------------------------------------------------------------------
    /**
     * EN: ENDPOINT TO DELETE AN EXERCISE
     * ES: ENDPOINT PARA ELIMINAR UN EJERCICIOS
     *
     * @param DeleteExerciseRequest $request
     * @return Response
     * @throws NonUniqueResultException
     */
    // ---------------------------------------------------------------------
    #[Route('/delete-exercise', name: 'exercise_delete', methods: ["POST"])]
    #[Permission(group: 'exercises', action: 'delete')]
    public function deleteExercise(DeleteExerciseRequest $request): Response
    {
        return $this->exerciseRequestService->deleteExerciseRequestService($request);
    }
    // ---------------------------------------------------------------------


}