<?php

namespace App\Request\Routine;

use App\Services\Routine\RoutineRegisterService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FinishRoutineRegisterRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $routineRegisterId;

    public function __construct(
        ValidatorInterface $validator,
        Security $token,
        JWTTokenManagerInterface $jwtManager,
        protected RoutineRegisterService $routineRegisterService,
        protected UserPermissionService $userPermissionService
    )
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    public function validate(): void
    {
        parent::validate();

        $routineRegister = $this->routineRegisterService->getRoutineRegisterById($this->routineRegisterId);
        if (!$routineRegister) {
            $this->addError('routineRegisterId', 'El registro de rutina especificado no existe');
            $this->resolveRequest();
        }
    }
}
