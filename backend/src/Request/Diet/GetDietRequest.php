<?php

namespace App\Request\Diet;

use App\Entity\Diet\Diet;
use App\Services\Diet\DietService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetDietRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Diet::class, message: 'La dieta introducida no existe en la base de datos')]
    public string $dietId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected DietService $dietService)
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

        $diet = $this->dietService->getDietById($this->dietId);

        if(!$diet)
        {
            $this->addError("dietId", "La dieta no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------

}