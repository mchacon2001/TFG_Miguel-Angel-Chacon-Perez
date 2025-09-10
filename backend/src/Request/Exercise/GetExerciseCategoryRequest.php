<?php

namespace App\Request\Exercise;

use App\Entity\Exercise\ExerciseCategory;
use App\Services\Exercise\ExerciseService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetExerciseCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: ExerciseCategory::class, message: 'La categoria de ejercicios introducida no existe en la base de datos')]
    public string $exerciseCategoryId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected ExerciseService $exerciseService)
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

        $exerciseCategory = $this->exerciseService->getExerciseCategoryById($this->exerciseCategoryId);

        if( !$exerciseCategory)
        {
            $this->addError("exerciseCategoryId", "La categoria de ejercicios no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------

}