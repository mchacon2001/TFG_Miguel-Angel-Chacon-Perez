<?php

namespace App\Services\Exercise;

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
use App\Services\Document\DocumentService;
use App\Services\User\RoleService;
use App\Services\User\UserService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use App\Utils\Tools\APIJsonResponse;
use App\Utils\Classes\JWTHandlerService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ExerciseRequestService extends JWTHandlerService
{

    public function __construct(
        protected ExerciseService $exerciseService,
        protected UserService $userService,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager,
        protected DocumentService $documentService,
        protected RoleService $roleService,
    )
    {
        parent::__construct($token, $jwtManager);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE CATEGORIES REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE CATEGORÍAS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE EXERCISE CATEGORY
     * ES: PETICIÓN PARA OBTENER LA CATEGORÍA DE EJERCICIO
     *
     * @param GetExerciseCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getCategoryRequestService(GetExerciseCategoryRequest $request): APIJsonResponse
    {
        $data = $this->exerciseService->getExerciseCategoryById($request->exerciseCategoryId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Categoria de ejercicio obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST EXERCISE CATEGORIES WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR CATEGORÍAS DE EJERCICIOS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListExerciseCategoriesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listCategoriesRequestService(ListExerciseCategoriesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        $data = $this->exerciseService->listCategoriesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de categorias de ejercicios obtenida con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE A EXERCISE CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UNA CATEGORÍA DE EJERCICIO CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateExerciseCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function createCategoryRequestService(CreateExerciseCategoryRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());

        $this->exerciseService->createCategoryService(
            name: $request->name,
            description: $request->description,
            user: $user
        );

        return new APIJsonResponse(
            [],
            true,
            'Categoria de ejercicios creada con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT AN EXERCISE CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UNA CATEGORÍA DE EJERCICIO CON LOS DATOS PROPORCIONADOS
     *
     * @param EditExerciseCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------
    public function editCategoryRequestService(EditExerciseCategoryRequest $request): APIJsonResponse
    {
        $exerciseCategory= $this->exerciseService->getExerciseCategoryByIdSimple($request->exerciseCategoryId);

        $this->exerciseService->editCategoryService(
            exerciseCategory: $exerciseCategory,
            name: $request->name,
            description: $request->description,
        );

        return new APIJsonResponse(
            [],
            true,
            'Categoria de ejercicio editada con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE AN EXERCISE CATEGORY
     * ES: PETICIÓN PARA ELIMINAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param DeleteExerciseCategoryRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteCategoryRequestService(DeleteExerciseCategoryRequest $request): APIJsonResponse
    {
        $exerciseCategory = $this->exerciseService->getExerciseCategoryByIdSimple($request->exerciseCategoryId);

        $this->exerciseService->deleteCategoryService($exerciseCategory);

        return new APIJsonResponse(
            [],
            true,
            'Categoria de ejercicios eliminada con éxito'
        );
    }
    // -------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISES REQUEST SERVICES
    // ES: SERVICIOS DE PETICIONES DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO GET THE EXERCISE
     * ES: PETICIÓN PARA OBTENER EL EJERCICIO
     *
     * @param GetExerciseRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------
    public function getExerciseRequestService(GetExerciseRequest $request): APIJsonResponse
    {
        $data = $this->exerciseService->getExerciseById($request->exerciseId, true);

        return new APIJsonResponse(
            $data,
            true,
            'Ejercicio obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO LIST EXERCISES WITH THE SELECTED FILTERS
     * ES: PETICIÓN PARA LISTAR EJERCICIOS CON LOS FILTROS SELECCIONADOS
     *
     * @param ListExercisesRequest $request
     * @return APIJsonResponse
     */
    // -----------------------------------------------------------
    public function listExercisesRequestService(ListExercisesRequest $request): APIJsonResponse
    {
        $filterService = new FilterService($request);

        if($this->getUser()->isSuperAdmin() || $this->getUser()->isAdmin())
        {
            $filterService->addFilter('isUser', null);
        }else
        {
            $filterService->addFilter('isUser', $this->getUser()->getId());
        }

        $data = $this->exerciseService->listExercisesService($filterService);

        return new APIJsonResponse(
            $data,
            true,
            'Listado de ejercicios obtenido con exito'
        );
    }
    // -----------------------------------------------------------


    // -----------------------------------------------------------
    /**
     * EN: REQUEST TO CREATE AN EXERCISE WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA CREAR UN EJERCICIO CON LOS DATOS PROPORCIONADOS
     *
     * @param CreateExerciseRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // -----------------------------------------------------------
    public function createExerciseRequestService(CreateExerciseRequest $request): APIJsonResponse
    {
        $user = $this->userService->getUserByIdSimple($this->getUser()->getUserIdentifier());
        $exerciseCategory = $this->exerciseService->getExerciseCategoryByIdSimple($request->exerciseCategoryId);

        if(!$exerciseCategory)
        {
            throw new APIException('La categoría de ejercicio no existe', code: 404);
        }

        $this->exerciseService->createExerciseService(
            name: $request->name,
            exerciseCategory: $exerciseCategory,
            user: $user,
            description: $request->description,
        );

        return new APIJsonResponse(
            [],
            true,
            'Ejercicio creado con éxito'
        );
    }
    // -----------------------------------------------------------


    // ------------------------------------------------------------
    /**
     * EN: REQUEST TO EDIT AN EXERCISE CATEGORY WITH THE PROVIDED DATA
     * ES: PETICIÓN PARA EDITAR UNA CATEGORÍA DE EJERCICIO CON LOS DATOS PROPORCIONADOS
     *
     * @param EditExerciseRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException|APIException
     */
    // ------------------------------------------------------------
    public function editExerciseRequestService(EditExerciseRequest $request): APIJsonResponse
    {
        $exerciseCategory = $this->exerciseService->getExerciseCategoryByIdSimple($request->exerciseCategoryId);
        $exercise = $this->exerciseService->getExerciseByIdSimple($request->exerciseId);

        if(!$exerciseCategory)
        {
            throw new APIException('La categoría de ejercicio no existe', code: 404);
        }

        if(!$exercise)
        {
            throw new APIException('El ejercicio no existe', code: 404);
        }

        $this->exerciseService->editExerciseService(
            exercise: $exercise,
            name: $request->name,
            exerciseCategory: $exerciseCategory,
            description: $request->description,
        );

        return new APIJsonResponse(
            [],
            true,
            'Ejercicio editado con éxito'
        );
    }
    // ------------------------------------------------------------


    // -------------------------------------------------------
    /**
     * EN: REQUEST TO DELETE AN EXERCISE
     * ES: PETICIÓN PARA ELIMINAR UN EJERCICIO
     *
     * @param DeleteExerciseRequest $request
     * @return APIJsonResponse
     * @throws NonUniqueResultException
     */
    // -------------------------------------------------------
    public function deleteExerciseRequestService(DeleteExerciseRequest $request): APIJsonResponse
    {
        $exercise = $this->exerciseService->getExerciseByIdSimple($request->exerciseId);

        $this->exerciseService->deleteExerciseService($exercise);

        return new APIJsonResponse(
            [],
            true,
            'Ejercicio eliminado con éxito'
        );
    }
    // -------------------------------------------------------
}