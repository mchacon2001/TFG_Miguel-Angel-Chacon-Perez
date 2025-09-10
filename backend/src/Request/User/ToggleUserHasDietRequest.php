<?php

namespace App\Request\User;


use App\Entity\User\UserHasDiet;
use App\Services\User\UserHasDietService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ToggleUserHasDietRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: UserHasDiet::class, message: "La dieta introducida no existe en la base de datos")]
    public string $userHasDietId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserHasDietService $userHasDietService)
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
        $diet = $this->userHasDietService->getById($this->userHasDietId);

        if(!$diet) {
            $this->addError("userHasDietId", "La dieta no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------
}