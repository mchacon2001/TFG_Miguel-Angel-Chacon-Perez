<?php

namespace App\Request\Routine;

use App\Services\Routine\RoutineRegisterExercisesService;
use App\Services\Routine\RoutineRegisterService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditRoutineRegisterExercisesRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $routineRegisterExerciseId;

    public ?int $reps = null;
    public ?float $weight = null;

    public function __construct(
        ValidatorInterface $validator,
        Security $token,
        JWTTokenManagerInterface $jwtManager,
        protected RoutineRegisterExercisesService $routineRegisterExercisesService,
        protected UserPermissionService $userPermissionService
    )
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    public function validate(): void
    {
        parent::validate();

        // Validar que el registro de ejercicio existe
        $routineRegisterExercise = $this->routineRegisterExercisesService->getRoutineRegisterExerciseById($this->routineRegisterExerciseId);
        if (!$routineRegisterExercise) {
            $this->addError('routineRegisterExerciseId', 'El registro de ejercicio de rutina especificado no existe');
            $this->resolveRequest();
        }
    }
}
