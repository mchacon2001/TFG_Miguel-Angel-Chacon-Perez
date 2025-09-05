<?php

namespace App\Request\Diet;

use App\Services\Diet\DietService;
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

class CreateDietRequest extends BaseRequest
{


    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;

    public array $dietFood = [];

    #[NotBlank]
    #[NotNull]
    public string $goal;

    public bool $toGainMuscle = false;
    public bool $toLoseWeight = false;
    public bool $toMaintainWeight = false;

    public ?string $userId = null;


    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserPermissionService $userPermissionService,
                                protected DietService $dietService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function validate(): void
    {
        parent::validate();

        $checkDietName = $this->dietService->getDietByName($this->name);

        if($checkDietName)
        {
            $this->addError('name', 'Ya existe una dieta con el nombre introducido');
            $this->resolveRequest();
        }
    }
}