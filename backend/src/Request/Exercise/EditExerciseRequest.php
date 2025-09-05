<?php

namespace App\Request\Exercise;

use App\Entity\Exercise\Exercise;
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

class EditExerciseRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Exercise::class, message: 'El ejercicio no existe')]
    public string $exerciseId;

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

        /**
         * @var \App\Entity\User\User $user
         */
        $user = $this->getUser();

        $checkExerciseName = $this->exerciseService->getExerciseByName($this->name);

        if($checkExerciseName && $checkExerciseName->getId() !== $this->exerciseId)
        {
            $this->addError('name', 'Ya existe un ejercicio con el nombre introducido');
            $this->resolveRequest();
        }

        if(!$user->isSuperAdmin() && !$user->isAdmin())
        {
            if($user->getId() !== $checkExerciseName->getUser()->getId())
            {
                $this->addError("exerciseId", "No tienes permisos para editar este ejercicio");
                $this->resolveRequest();
            }

            if($user->getId() !== $this->exerciseService->getExerciseById($this->exerciseId)->getUser()->getId())
            {
                $this->addError("exerciseId", "No tienes permisos para editar este ejercicio");
                $this->resolveRequest();
            }
        }



        $checkExercise = $this->exerciseService->getExerciseById($this->exerciseId);
        if(!$checkExercise)
        {
            $this->addError("exerciseId", "El ejercicio no existe");
            $this->resolveRequest();
        }
    }

}