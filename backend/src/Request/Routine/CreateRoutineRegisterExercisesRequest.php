<?php

namespace App\Request\Routine;

use App\Services\Exercise\ExerciseService;
use App\Services\Routine\RoutineRegisterService;
use App\Services\Routine\RoutineService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRoutineRegisterExercisesRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $routineRegisterId;

    #[NotBlank]
    #[NotNull]
    public string $exerciseId;

    

    #[NotBlank]
    #[NotNull]
    public int $sets;

    #[NotBlank]
    #[NotNull]
    public int $reps;

    public ?float $weight = null;

    public function __construct(
        ValidatorInterface $validator,
        Security $token,
        JWTTokenManagerInterface $jwtManager,
        protected RoutineService $routineService,
        protected UserPermissionService $userPermissionService,
        protected RoutineRegisterService $routineRegisterService,
        protected ExerciseService $exerciseService
    )
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    public function validate(): void
    {
        parent::validate();

        // Validar que el registro de rutina existe
        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($this->routineRegisterId);
        if (!$routineRegister) {
            $this->addError('routineRegisterId', 'El registro de rutina especificado no existe');
            $this->resolveRequest();
        }

        // Validar que el ejercicio existe
        $exercise = $this->exerciseService->getExerciseById($this->exerciseId);
        if (!$exercise) {
            $this->addError('exerciseId', 'El ejercicio especificado no existe');
            $this->resolveRequest();
        }
    }
}