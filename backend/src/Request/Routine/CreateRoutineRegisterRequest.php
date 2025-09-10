<?php

namespace App\Request\Routine;

use App\Services\Routine\RoutineService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRoutineRegisterRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $routineId;

    #[NotBlank]
    #[NotNull]
    public int $day;

    public function __construct(
        ValidatorInterface $validator,
        Security $token,
        JWTTokenManagerInterface $jwtManager,
        protected RoutineService $routineService,
        protected UserPermissionService $userPermissionService
    )
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    public function validate(): void
    {
        parent::validate();

        $routine = $this->routineService->getRoutineById($this->routineId);
        if (!$routine) {
            $this->addError('routineId', 'La rutina especificada no existe');
            $this->resolveRequest();
        }
    }
}