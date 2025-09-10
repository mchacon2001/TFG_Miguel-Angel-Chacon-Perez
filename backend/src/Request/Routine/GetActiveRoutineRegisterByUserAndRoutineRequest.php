<?php

namespace App\Request\Routine;

use App\Entity\Routine\Routine;
use App\Entity\User\User;
use App\Services\Routine\RoutineService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetActiveRoutineRegisterByUserAndRoutineRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Routine::class, message: 'La rutina introducida no existe en la base de datos')]
    public string $routineId;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: 'El usuario introducido no existe en la base de datos')]
    public string $userId;

    // Remove day requirement since we want to find any active routine
    public ?int $day = null;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected RoutineService $routineService)
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

        $routine = $this->routineService->getRoutineById($this->routineId);

        if(!$routine)
        {
            $this->addError("routineCategoryId", "La rutina no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------

}