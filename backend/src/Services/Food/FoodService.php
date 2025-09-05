<?php

namespace App\Services\Food;

use App\Entity\Food\Food;
use App\Entity\User\User;
use App\Repository\Food\FoodRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FoodService
{

    /**
     * @var FoodRepository|EntityRepository
     */
    protected FoodRepository|EntityRepository $foodRepository;

    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
    )
    {
        $this->foodRepository = $em->getRepository(Food::class);
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: FOOD SERVICES
    // ES: SERVICIOS DE COMIDAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET FOOD BY ID
     * ES: SERVICIO PARA OBTENER COMIDA POR ID
     *
     * @param string $foodId
     * @param bool $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getFoodById(string $foodId, ?bool $array = false): null|Food|array
    {
        return $this->foodRepository->findById($foodId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET FOOD BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA COMIDA POR ID (MÉTODO SIMPLE)
     *
     * @param string $foodId
     * @param bool $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getFoodByIdSimple(string $foodId, ?bool $array = false): null|Food|array
    {
        return $this->foodRepository->findSimpleFoodById($foodId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET FOOD BY NAME
     * ES: SERVICIO PARA OBTENER COMIDA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Food|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getFoodByName(string $name, ?bool $array = false): Food|array|null
    {
        return $this->foodRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST FOOD CATEGORIES
     * ES: SERVICIO PARA LISTAR LAS CATEGORIAS DE ALIMENTOS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listFoodService(FilterService $filterService): array
    {
        return $this->foodRepository->list($filterService);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE FOOD
     * ES: SERVICIO PARA CREAR COMIDA
     *
     * @param string $name
     * @param User $user
     * @param string|null $description
     * @param float $calories
     * @param float $proteins
     * @param float $carbs
     * @param float $fats
     * @return Food|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function createFoodService(
        string $name,
        User $user,
        ?string $description = null,
        float $calories,
        float $proteins,
        float $carbs,
        float $fats,
    ): Food|null
    {
        return $this->foodRepository->create(
            name: $name,
            user: $user,
            description: $description,
            calories: $calories,
            proteins: $proteins,
            carbs: $carbs,
            fats: $fats,
        );
    }

    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT FOOD
     * ES: SERVICIO PARA EDITAR COMIDA
     *
     * @param Food $food
     * @param string $name
     * @param string|null $description
     * @param float $calories
     * @param float $proteins
     * @param float $carbs
     * @param float $fats
     * 
     * @return Food|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editFoodService(
        Food $food,
        string $name,
        ?string $description = null,
        float $calories,
        float $proteins,
        float $carbs,
        float $fats,
    ): Food|null
    {
        $foodEdited = $this->foodRepository->edit(
            food: $food,
            name: $name,
            description: $description,
            calories: $calories,
            proteins: $proteins,
            carbs: $carbs,
            fats: $fats,
        );

        return $foodEdited;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE AN FOOD
     * ES: SERVICIO PARA ELIMINAR UN FOOD
     *
     * @param Food $food
     * @return Food|null
     */
    // ------------------------------------------------------------------------
    public function deleteFoodService(Food $food): Food|null
    {
        return $this->foodRepository->remove($food);
    }
    // ------------------------------------------------------------------------

}