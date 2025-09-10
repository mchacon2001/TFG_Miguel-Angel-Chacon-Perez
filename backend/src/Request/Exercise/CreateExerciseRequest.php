<?php

namespace App\Request\Exercise;

use App\Entity\Exercise\ExerciseCategory;
use App\Services\Exercise\ExerciseService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateExerciseRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: ExerciseCategory::class, message: 'La categoria de ejercicios no existe')]
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
     */
    public function validate(): void
    {
        parent::validate();

        $checkExerciseName = $this->exerciseService->getExerciseByName($this->name);

        if($checkExerciseName)
        {
            $this->addError('name', 'Ya existe un ejercicio con el nombre introducido');
            $this->resolveRequest();
        }
    }
}