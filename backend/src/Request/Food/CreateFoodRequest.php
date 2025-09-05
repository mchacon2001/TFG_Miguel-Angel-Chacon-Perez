<?php

namespace App\Request\Food;

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

class CreateFoodRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $name;
    public ?string $description;

    #[NotBlank]
    #[NotNull]
    public float $calories;

    #[NotBlank]
    #[NotNull]
    public float $proteins;
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

        $checkFoodName = $this->foodService->getFoodByName($this->name, false);

        if($checkFoodName)
        {
            $this->addError('name', 'Ya existe un alimento con el nombre introducido');
            $this->resolveRequest();
        }
    }
}