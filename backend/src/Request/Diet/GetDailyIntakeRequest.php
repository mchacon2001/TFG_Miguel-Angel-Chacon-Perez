<?php

namespace App\Request\Diet;


use App\Services\User\UserService;
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
use App\Entity\User\User;

class GetDailyIntakeRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: "El usuario no existe en la base de datos")]
    public string $userId;

    #[NotBlank]
    #[NotNull]
    public string $date;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService)
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
    }
    // -----------------------------------------------------------------
}