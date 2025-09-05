<?php

namespace App\Request\Exercise;

use App\Entity\Exercise\ExerciseCategory;
use App\Services\Exercise\ExerciseService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditExerciseCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: ExerciseCategory::class, message: 'La categoría de ejercicio no existe')]
    public string $exerciseCategoryId;

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected ExerciseService $exerciseService,
                                protected UserPermissionService $userPermissionService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws NonUniqueResultException
     * @throws JWTDecodeFailureException
     */
    public function validate(): void
    {
        parent::validate();

        $exerciseCategory = $this->exerciseService->getExerciseCategoryById($this->exerciseCategoryId);

        if( !$exerciseCategory) {
            $this->addError("exerciseCategoryId", "La categoria de ejercicio no existe");
            $this->resolveRequest();
        }

        $checkExerciseCategoryName = $this->exerciseService->getExerciseCategoryByName($this->name);

        if($checkExerciseCategoryName && $checkExerciseCategoryName->getId() !== $this->exerciseCategoryId)
        {
            $this->addError('name', 'Ya existe una categoria de ejercicios con el nombre introducido');
            $this->resolveRequest();
        }
    }

}