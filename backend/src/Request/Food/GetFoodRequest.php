<?php

namespace App\Request\Food;

use App\Entity\Food\Food;
use App\Services\Food\FoodService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetFoodRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Food::class, message: 'El alimento introducido no existe en la base de datos')]
    public string $foodId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected FoodService $foodService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }


    // -----------------------------------------------------------------
    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------------
    public function validate(): void
    {
        parent::validate();

        $food = $this->foodService->getFoodById($this->foodId);

        if(!$food)
        {
            $this->addError("foodId", "El alimento no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------

}