<?php

namespace App\Request\Food;

use App\Entity\Food\Food;
use App\Services\Food\FoodService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditFoodRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Food::class, message: 'El alimento no existe')]
    public string $foodId;

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    #[NotBlank]
    #[NotNull]
    public ?float $calories;
    
    #[NotBlank]
    #[NotNull]
    public ?float $proteins;

    #[NotBlank]
    #[NotNull]
    public float $carbs;

    #[NotBlank]
    #[NotNull]
    public float $fats;
    


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected FoodService $foodService,
                                protected UserPermissionService $userPermissionService)
                                {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function validate(): void
    {
        parent::validate();

        /**
         * @var \App\Entity\User\User $user
         */
        $user = $this->getUser();

        $checkFoodName = $this->foodService->getFoodByName($this->name);

        if($checkFoodName && $checkFoodName->getId() !== $this->foodId)
        {
            $this->addError('name', 'Ya existe un alimento con el nombre introducido');
            $this->resolveRequest();
        }

        $checkFood = $this->foodService->getFoodById($this->foodId);
        if(!$checkFood)
        {
            $this->addError('foodId', 'El alimento no existe');
            $this->resolveRequest();
        }

        if(!$user->isSuperAdmin() && !$user->isAdmin())
        {
            if($user->getId() !== $checkFoodName->getUser()->getId())
            {
                $this->addError("foodId", "No tienes permisos para editar este alimento");
                $this->resolveRequest();
            }
            if($user->getId() !== $this->foodService->getFoodById($this->foodId)->getUser()->getId())
            {   
                $this->addError("foodId", "No tienes permisos para editar este alimento");
                $this->resolveRequest();
            }
        }

    }

}