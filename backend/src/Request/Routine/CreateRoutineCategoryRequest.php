<?php

namespace App\Request\Routine;

use App\Services\Routine\RoutineService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRoutineCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected RoutineService $routineService,
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
        $checkRoutineCategoryName = $this->routineService->getRoutineCategoryByName($this->name);

        if($checkRoutineCategoryName)
        {
            $this->addError('name', 'Ya existe una categoria de rutina con el nombre introducido');
            $this->resolveRequest();
        }
    }

}