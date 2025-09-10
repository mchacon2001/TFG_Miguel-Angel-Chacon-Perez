<?php

namespace App\Request\Diet;

use App\Entity\Diet\Diet;
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

class EditDietRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Diet::class, message: 'La dieta introducida no existe en la base de datos')]
    public string $dietId;

    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;
    public array $dietFood = [];

    #[NotBlank]
    #[NotNull]
    public string $goal;

    // Only keep the three main flags
    public bool $toGainMuscle = false;
    public bool $toLoseWeight = false;
    public bool $toMaintainWeight = false;


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

        /**
         * @var \App\Entity\User\User $user
         */
        $user = $this->getUser();

        $checkDietName = $this->dietService->getDietByName($this->name);

        if($checkDietName && $checkDietName->getId() !== $this->dietId)
        {
            $this->addError('name', 'Ya existe una dieta con el nombre introducido');
            $this->resolveRequest();
        }

        if(!$user->isSuperAdmin() && !$user->isAdmin())
        {
            if($user->getId() !== $checkDietName->getUser()->getId())
            {
                $this->addError("dietId", "No tienes permisos para editar esta dieta");
                $this->resolveRequest();
            }

            if($user->getId() !== $this->dietService->getDietById($this->dietId)->getUser()->getId())
            {
                $this->addError("dietId", "No tienes permisos para editar esta dieta");
                $this->resolveRequest();
            }
        }

    }
}