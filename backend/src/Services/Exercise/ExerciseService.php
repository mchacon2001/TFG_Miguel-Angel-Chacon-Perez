<?php

namespace App\Services\Exercise;

use App\Entity\Exercise\Exercise;
use App\Entity\Exercise\ExerciseCategory;
use App\Entity\User\User;
use App\Repository\Exercise\ExerciseCategoryRepository;
use App\Repository\Exercise\ExerciseRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ExerciseService
{
    /**
     * @var ExerciseCategoryRepository|EntityRepository
     */
    protected ExerciseCategoryRepository|EntityRepository $exerciseCategoryRepository;

    /**
     * @var ExerciseRepository|EntityRepository
     */
    protected ExerciseRepository|EntityRepository $exerciseRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
    )
    {
        $this->exerciseCategoryRepository = $em->getRepository(ExerciseCategory::class);
        $this->exerciseRepository = $em->getRepository(Exercise::class);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE CATEGORIES SERVICES
    // ES: SERVICIOS DE CATEGORÍAS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET EXERCISE CATEGORY BY ID
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE EJERCICIO POR ID
     *
     * @param string $exerciseCategoryId
     * @param bool $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseCategoryById(string $exerciseCategoryId, ?bool $array = false): null|ExerciseCategory|array
    {
        return $this->exerciseCategoryRepository->findById($exerciseCategoryId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET EJERCICIO CATEGORY BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE EJERCICIO POR ID (MÉTODO SIMPLE)
     *
     * @param string $exerciseCategoryId
     * @param bool $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseCategoryByIdSimple(string $exerciseCategoryId, ?bool $array = false): null|ExerciseCategory|array
    {
        return $this->exerciseCategoryRepository->findSimpleExerciseCategoryById($exerciseCategoryId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET AN EXERCISE CATEGORY BY NAME
     * ES: SERVICIO PARA OBTENER UNA CATEGORÍA DE EJERCICIO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return ExerciseCategory|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseCategoryByName(string $name, ?bool $array = false): ExerciseCategory|array|null
    {
        return $this->exerciseCategoryRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST EXERCISE CATEGORIES
     * ES: SERVICIO PARA LISTAR LAS CATEGORIAS DE EJERCICIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listCategoriesService(FilterService $filterService): array
    {
        return $this->exerciseCategoryRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE AN EXERCISE CATEGORY
     * ES: SERVICIO PARA CREAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param string $name
     * @param string|null $description
     * @param User $user
     * @return ExerciseCategory|null
     */
    // ------------------------------------------------------------------------
    public function createCategoryService(
        string $name,
        ?string $description,
        User $user
    ): ExerciseCategory|null
    {
        return $this->exerciseCategoryRepository->create(
            name: $name,
            description: $description,
            user: $user
        );
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT AN EXERCISE CATEGORY
     * ES: SERVICIO PARA EDITAR UNA CATEGORÍA DE EJERCICIOS
     *
     * @param ExerciseCategory $exerciseCategory
     * @param string $name
     * @param string|null $description
     * @return ExerciseCategory|null
     */
    // ------------------------------------------------------------------------
    public function editCategoryService(
        ExerciseCategory $exerciseCategory,
        string $name,
        ?string $description,
    ): ExerciseCategory|null
    {
        return $this->exerciseCategoryRepository->edit(
            exerciseCategory: $exerciseCategory,
            name: $name,
            description: $description,
        );
    }
    // ------------------------------------------------------------------------





    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: EXERCISE SERVICES
    // ES: SERVICIOS DE EJERCICIOS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET EXERCISE BY ID
     * ES: SERVICIO PARA OBTENER UN EJERCICIO POR ID
     *
     * @param string $exerciseId
     * @param bool $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseById(string $exerciseId, ?bool $array = false): null|Exercise|array
    {
        return $this->exerciseRepository->findById($exerciseId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET EXERCISE BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UN EJERCICIO POR ID (MÉTODO SIMPLE)
     *
     * @param string $exerciseId
     * @param bool $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseByIdSimple(string $exerciseId, ?bool $array = false): null|Exercise|array
    {
        return $this->exerciseRepository->findSimpleExerciseById($exerciseId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET AN EXERCISE BY NAME
     * ES: SERVICIO PARA OBTENER UN EJERCICIO POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Exercise|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getExerciseByName(string $name, ?bool $array = false): Exercise|array|null
    {
        return $this->exerciseRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST EXERSICES CATEGORIES
     * ES: SERVICIO PARA LISTAR LAS CATEGORIAS DE EJERCICIOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listExercisesService(FilterService $filterService): array
    {
        return $this->exerciseRepository->list($filterService);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE AN EXERCISE
     * ES: SERVICIO PARA CREAR UN EJERCICIO
     *
     * @param string $name
     * @param ExerciseCategory $exerciseCategory
     * @param User $user
     * @return Exercise|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function createExerciseService(
        string $name,
        ExerciseCategory $exerciseCategory,
        User $user,
        ?string $description = null
    ): Exercise|null
    {
        return $this->exerciseRepository->create(
            name: $name,
            exerciseCategory: $exerciseCategory,
            user: $user,
            description: $description
        );
    }
    
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT AN EXERCISE
     * ES: SERVICIO PARA EDITAR UN EJERCICIO
     *
     * @param Exercise $exercise
     * @param string $name
     * @param ExerciseCategory $exerciseCategory
     * @return Exercise|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editExerciseService(
        Exercise $exercise,
        string $name,
        ExerciseCategory $exerciseCategory,
        ?string $description = null
    ): Exercise|null
    {
        

        $exerciseEdited = $this->exerciseRepository->edit(
            exercise: $exercise,
            name: $name,
            exerciseCategory: $exerciseCategory,
            description: $description
        );

        return $exerciseEdited;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE AN EXERCISE
     * ES: SERVICIO PARA ELIMINAR UN EJERCICIO
     *
     * @param Exercise $exercise
     * @return Exercise|null
     */
    // ------------------------------------------------------------------------
    public function deleteExerciseService(Exercise $exercise): Exercise|null
    {
        return $this->exerciseRepository->remove($exercise);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE AN EXERCISE CATEGORY
     * ES: SERVICIO PARA ELIMINAR UNA CATEGORÍA DE EJERCICIO
     *
     * @param ExerciseCategory $exerciseCategory
     * @return ExerciseCategory|null
     */
    // ------------------------------------------------------------------------
    public function deleteCategoryService(ExerciseCategory $exerciseCategory): ExerciseCategory|null
    {
        return $this->exerciseCategoryRepository->remove($exerciseCategory);
    }
    // ------------------------------------------------------------------------
}