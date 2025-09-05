<?php

namespace App\Request\Routine;

use App\Entity\Routine\RoutineCategory;
use App\Services\Routine\RoutineService;
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

class EditRoutineCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: RoutineCategory::class, message: 'La categoría de rutina no existe')]
    public string $routineCategoryId;

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
     * @throws JWTDecodeFailureException
     */
    public function validate(): void
    {
        parent::validate();

        $routineCategory = $this->routineService->getRoutineCategoryById($this->routineCategoryId);

        if( !$routineCategory) {
            $this->addError("routineCategoryId", "La categoria de rutina no existe");
            $this->resolveRequest();
        }

        $checkRoutineCategoryName = $this->routineService->getRoutineCategoryByName($this->name);

        if($checkRoutineCategoryName && $checkRoutineCategoryName->getId() !== $this->routineCategoryId)
        {
            $this->addError('name', 'Ya existe una categoria de rutina con el nombre introducido');
            $this->resolveRequest();
        }
    }

}