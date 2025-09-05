<?php

namespace App\Request\Routine;

use App\Entity\Routine\RoutineCategory;
use App\Services\Routine\RoutineService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateRoutineRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: RoutineCategory::class, message: 'La rutina introducida no existe en la base de datos')]
    public string $routineCategoryId;

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    public array $routineExercises = [];
    public ?bool $toGainMuscle = false;
    public ?bool $toLoseWeight = false;
    public ?bool $toMaintainWeight = false;
    public ?bool $toImprovePhysicalHealth = false;
    public ?bool $toImproveMentalHealth = false;
    public ?bool $fixShoulder = false;
    public ?bool $fixKnees = false;
    public ?bool $fixBack = false;
    public ?bool $rehab = false;
    public ?string $userId = null;


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserPermissionService $userPermissionService,
                                protected RoutineService $routineService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function validate(): void
    {
        parent::validate();

        $checkRoutineName = $this->routineService->getRoutineByName($this->name);

        if($checkRoutineName)
        {
            $this->addError('name', 'Ya existe una rutina con el nombre introducido');
            $this->resolveRequest();
        }
    }
}