<?php

namespace App\Services\Diet;

use App\Entity\Diet\Diet;
use App\Entity\Diet\DietHasFood;
use App\Entity\Food\Food;
use App\Entity\User\User;
use App\Repository\Diet\DietHasFoodRepository;
use App\Repository\Diet\DietRepository;
use App\Repository\Food\FoodRepository;
use App\Services\Document\DocumentService;
use App\Utils\Exceptions\APIException;
use App\Utils\Tools\FilterService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DietService
{
    /**
     * @var FoodRepository|EntityRepository
     */
    protected FoodRepository|EntityRepository $foodRepository;

    /**
     * @var DietRepository|EntityRepository
     */
    protected DietRepository|EntityRepository $dietRepository;

    /**
     * @var DietHasFoodRepository|EntityRepository
     */
    protected DietHasFoodRepository|EntityRepository $dietHasFoodRepository;

    private MessageBusInterface $bus;


    public function __construct(
        protected EntityManagerInterface $em,
        protected DocumentService $documentService,
        protected UserPasswordHasherInterface $encoder,
        MessageBusInterface $bus
    )
    {
        $this->foodRepository = $em->getRepository(Food::class);
        $this->dietRepository = $em->getRepository(Diet::class);
        $this->dietHasFoodRepository = $em->getRepository(DietHasFood::class);
        $this->bus = $bus;
    }

    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: DIET SERVICES
    // ES: SERVICIOS DE DIETAS
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET DIET BY ID
     * ES: SERVICIO PARA OBTENER UNA DIETA POR ID
     *
     * @param string $dietId
     * @param bool $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getDietById(string $dietId, ?bool $array = false): null|Diet|array
    {
        return $this->dietRepository->findById($dietId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET DIET BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA DIETA POR ID (MÉTODO SIMPLE)
     *
     * @param string $dietId
     * @param bool $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getDietByIdSimple(string $dietId, ?bool $array = false): null|Diet|array
    {
        return $this->dietRepository->findSimpleDietById($dietId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET DIET BY NAME
     * ES: SERVICIO PARA OBTENER UNA DIETA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getDietByName(string $name, ?bool $array = false): Diet|array|null
    {
        return $this->dietRepository->findDietByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET ALL DIET
     * ES: SERVICIO PARA OBTENER TODAS LAS DIETAS
     *
     * @param bool|null $array
     * @return array|Diet
     */
    // ------------------------------------------------------------------------
    public function getAllDiet(?bool $array = false): array|Diet
    {
        return $this->dietRepository->getAllDiet($array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A DIET BY NAME
     * ES: SERVICIO PARA OBTENER UNA DIETA POR NOMBRE
     *
     * @param string $name
     * @param bool|null $array
     * @return Diet|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getAllByName(string $name, ?bool $array = false): Diet|array|null
    {
        return $this->dietRepository->findByName($name, $array);
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO LIST DIET
     * ES: SERVICIO PARA LISTAR LAS DIETAS
     *
     * @param FilterService $filterService
     * @return array
     */
    // ------------------------------------------------------------------------
    public function listDietService(FilterService $filterService): array
    {
        return $this->dietRepository->list($filterService);
    }
    // ------------------------------------------------------------------------



    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A DIET
     * ES: SERVICIO PARA CREAR UNA DIETA
     *
     * @param string $name
     * @param string|null $description
     * @param string $goal
     * @param User $user
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Diet|null
     * @throws NonUniqueResultException
     * @throws APIException
     */
    // ------------------------------------------------------------------------
    public function createDietService(
        string $name,
        ?string $description,
        string $goal,
        User $user,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Diet|null
    {
        $dietCreated =  $this->dietRepository->create(
            name: $name,
            description: $description,
            user: $user,
            goal: $goal,
            toGainMuscle: $toGainMuscle,
            toLoseWeight: $toLoseWeight,
            toMaintainWeight: $toMaintainWeight,
            toImprovePhysicalHealth: $toImprovePhysicalHealth,
            toImproveMentalHealth: $toImproveMentalHealth,
            fixShoulder: $fixShoulder,
            fixKnees: $fixKnees,
            fixBack: $fixBack,
            rehab: $rehab
        );
        return $dietCreated;
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO EDIT A DIET
     * ES: SERVICIO PARA EDITAR UNA DIETA
     *
     * @param Diet $diet
     * @param string $name
     * @param string|null $description
     * @param string $goal
     * @param bool $toGainMuscle
     * @param bool $toLoseWeight
     * @param bool $toMaintainWeight
     * @param bool $toImprovePhysicalHealth
     * @param bool $toImproveMentalHealth
     * @param bool $fixShoulder
     * @param bool $fixKnees
     * @param bool $fixBack
     * @param bool $rehab
     * @return Diet|null
     * @throws APIException
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function editDietService(
        Diet $diet,
        string $name,
        ?string $description,
        string $goal,
        bool $toGainMuscle,
        bool $toLoseWeight,
        bool $toMaintainWeight,
        bool $toImprovePhysicalHealth,
        bool $toImproveMentalHealth,
        bool $fixShoulder,
        bool $fixKnees,
        bool $fixBack,
        bool $rehab
    ): Diet|null
    {
        $dietEdited = $this->dietRepository->edit(
            diet: $diet,
            name: $name,
            description: $description,
            goal: $goal,
            toGainMuscle: $toGainMuscle,
            toLoseWeight: $toLoseWeight,
            toMaintainWeight: $toMaintainWeight,
            toImprovePhysicalHealth: $toImprovePhysicalHealth,
            toImproveMentalHealth: $toImproveMentalHealth,
            fixShoulder: $fixShoulder,
            fixKnees: $fixKnees,
            fixBack: $fixBack,
            rehab: $rehab
        );

        return $dietEdited;
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO DELETE A DIET
     * ES: SERVICIO PARA ELIMINAR UNA DIETA
     *
     * @param Diet $diet
     * @return Diet|null
     */
    // ------------------------------------------------------------------------
    public function deleteDietService(Diet $diet): Diet|null
    {
        return $this->dietRepository->remove($diet);
    }
    // ------------------------------------------------------------------------


    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------
    // EN: RELATION FOOD-DIET SERVICES
    // ES: SERVICIOS DE RELACIÓN COMIDA-DIETA
    // ---------------------------------------------------------------------
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // ---------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET RELATION BY ID
     * ES: SERVICIO PARA OBTENER UNA RELACIÓN POR ID
     *
     * @param string $foodHasDietId
     * @param bool $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getFoodHasDietById(string $foodHasDietId, ?bool $array = false): null|DietHasFood|array
    {
        return $this->dietHasFoodRepository->findById($foodHasDietId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET RELATION BY ID (SIMPLE METHOD)
     * ES: SERVICIO PARA OBTENER UNA RELACIÓN POR ID (MÉTODO SIMPLE)
     *
     * @param string $dietHasFoodId
     * @param bool $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getSimpleDietHasFoodById(string $dietHasFoodId, ?bool $array = false): null|DietHasFood|array
    {
        return $this->dietHasFoodRepository->findSimpleDietHasFoodById($dietHasFoodId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE RELATION BY DIET, FOOD
     * ES: SERVICIO PARA OBTENER LA RELACIÓN POR DIETA, COMIDA
     *
     * @param string $dietId
     * @param string $foodId
     * @param bool|null $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getDietHasFoodByDietAndFood(string $dietId, string $foodId, ?bool $array = false): DietHasFood|array|null
    {
        return $this->dietHasFoodRepository->findByDietAndFood($dietId, $foodId, $array);
    }
    // ------------------------------------------------------------------------


    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET THE RELATION BY DIET, FOOD, FOOD IDENTIFIER
     * ES: SERVICIO PARA OBTENER LA RELACIÓN POR DIETA, COMIDA, CÓDIGO DE COMIDA
     *
     * @param string $foodIdentifier
     * @param string $dietId
     * @param string $foodId
     * @param bool|null $array
     * @return DietHasFood|array|null
     * @throws NonUniqueResultException
     */
    // ------------------------------------------------------------------------
    public function getDietHasFoodByFoodIdentifierAndDietAndFood(string $foodIdentifier, string $dietId, string $foodId, ?bool $array = false): DietHasFood|array|null
    {
        return $this->dietHasFoodRepository->findByFoodIdentifierAndDietAndFood($foodIdentifier, $dietId, $foodId, $array);
    }
    // ------------------------------------------------------------------------



    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO CREATE A RELATION
     * ES: SERVICIO PARA CREAR UNA RELACIÓN
     *
     * @param Diet $diet
     * @param Food $food
     * @param int $sets
     *
     * @return DietHasFood|null
     */
    // ------------------------------------------------------------------------
    public function createDietHasFoodService(
        Diet $diet,
        Food $food,
        string $dayOfWeek,
        string $mealType,
        float $amount,
        ?string $notes = null
        

    ): DietHasFood|null
    {
        return $this->dietHasFoodRepository->create(
            food: $food,
            diet: $diet,
            dayOfWeek: $dayOfWeek,
            mealType: $mealType,
            amount: $amount,
            notes: $notes
        );
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------

     public function deleteDietHasFoodService(
        DietHasFood $dietHasFood
    ): void
    {
        $this->dietHasFoodRepository->remove($dietHasFood);
    }

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO FORMAT DIET WITH FOOD
     * ES: SERVICIO PARA FORMATEAR UNA DIETA CON COMIDA
     *
     * @param Diet $diet
     * @return array
     */
    // ------------------------------------------------------------------------

    public function formatStructuredDiet(Diet $diet): array
    {
        $structured = [
            'id' => $diet->getId(),
            'name' => $diet->getName(),
            'description' => $diet->getDescription(),
            'goal' => $diet->getGoal(),
            'days' => [],
            'creator' => $diet->getUser()->getId()
        ];

        foreach ($diet->getDietHasFood() as $relation) {
            $day = $relation->getDayOfWeek();
            $meal = $relation->getMealType();

            if (!isset($structured['days'][$day])) {
                $structured['days'][$day] = [];
            }

            if (!isset($structured['days'][$day][$meal])) {
                $structured['days'][$day][$meal] = [];
            }

            $structured['days'][$day][$meal][] = [
                'food' => [
                    'id' => $relation->getFood()->getId(),
                    'name' => $relation->getFood()->getName(),
                    'description' => $relation->getFood()->getDescription(),
                    'calories' => $relation->getFood()->getCalories(),
                    'proteins' => $relation->getFood()->getProteins(),
                    'carbs' => $relation->getFood()->getCarbs(),
                    'fats' => $relation->getFood()->getFats(),
                ],
                'amount' => $relation->getAmount(),
                'notes' => $relation->getNotes(),
            ];
        }

        return $structured;
    }

    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    /**
     * EN: SERVICE TO GET A DIET STRUCTURED
     * ES: SERVICIO PARA OBTENER UNA DIETA ESTRUCTURADA
     *
     * @param string $dietId
     * @return array|null
     */
    // ------------------------------------------------------------------------
    public function getStructuredDiet(string $dietId): ?array
    {
        $diet = $this->getDietById($dietId);

        if (!$diet) {
            return null;
        }

        return $this->formatStructuredDiet($diet);
    }
    // ------------------------------------------------------------------------
    /**
     * EN/ES: Formatea una dieta para el frontend de edición (estructura esperada por DietForm)
     * @param Diet $diet
     * @return array
     */
    public function formatDietForEdit(Diet $diet): array
    {
        $DAYS_OF_WEEK = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
        $MEALS = ["Desayuno", "Media Mañana", "Almuerzo", "Merienda", "Cena"];

        $dietFood = [];
        foreach ($DAYS_OF_WEEK as $day) {
            $meals = [];
            foreach ($MEALS as $meal) {
                $meals[] = [
                    'name' => $meal,
                    'foods' => []
                ];
            }
            $dietFood[] = [
                'day' => $day,
                'meals' => $meals
            ];
        }

        foreach ($diet->getDietHasFood() as $relation) {
            $dayIndex = array_search($relation->getDayOfWeek(), $DAYS_OF_WEEK);
            $mealIndex = array_search($relation->getMealType(), $MEALS);
            if ($dayIndex !== false && $mealIndex !== false) {
                $dietFood[$dayIndex]['meals'][$mealIndex]['foods'][] = [
                    'foodId' => $relation->getFood()->getId(),
                    'quantity' => $relation->getAmount(),
                ];
            }
        }

        return [
            'id' => $diet->getId(),
            'name' => $diet->getName(),
            'description' => $diet->getDescription(),
            'goal' => $diet->getGoal(),
            'dietFood' => $dietFood,
            // Add flag values for editing
            'toGainMuscle' => $diet->isToGainMuscle(),
            'toLoseWeight' => $diet->isToLoseWeight(),
            'toMaintainWeight' => $diet->isToMaintainWeight(),
        ];
    }

    /**
     * EN/ES: Servicio para obtener una dieta formateada para edición
     * @param string $dietId
     * @return array|null
     */
    public function getDietForEdit(string $dietId): ?array
    {
        $diet = $this->getDietByIdSimple($dietId);
        if (!$diet) {
            return null;
        }
        return $this->formatDietForEdit($diet);
    }
    // ------------------------------------------------------------------------
        
}