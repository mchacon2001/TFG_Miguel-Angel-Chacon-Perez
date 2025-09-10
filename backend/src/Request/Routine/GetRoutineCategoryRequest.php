<?php

namespace App\Request\Routine;

use App\Entity\Routine\RoutineCategory;
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

class GetRoutineCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: RoutineCategory::class, message: 'La categoria de rutina introducida no existe en la base de datos')]
    public string $routineCategoryId;

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

        $routineCategory = $this->routineService->getRoutineCategoryById($this->routineCategoryId);

        if(!$routineCategory)
        {
            $this->addError("routineCategoryId", "La categoria de rutina no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------

}