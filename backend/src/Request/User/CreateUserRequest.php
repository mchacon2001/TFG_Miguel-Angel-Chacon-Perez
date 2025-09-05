<?php

namespace App\Request\User;

use App\Entity\User\Role;
use App\Services\User\UserPermissionService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use App\Utils\Validator\FilterRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;


class CreateUserRequest extends FilterRequest
{
    #[NotBlank]
    #[NotNull]
    #[Regex(pattern: '/^[a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]+$/u', message: 'El formato es incorrecto, sólo se permiten caracteres alfabéticos válidos')]
    public string $name;


    #[NotBlank]
    #[NotNull]
    public string $email;

    #[NotBlank]
    #[NotNull]
    public string $targetWeight;

    #[NotBlank]
    #[NotNull]
    public string $sex;

    #[NotBlank]
    #[NotNull]
    public string $birthdate;


    public ?int $height = null;


    public ?float $weight = null;

    #[NotBlank]
    #[NotNull]
    public string $password;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Role::class, message: 'El rol seleccionado no existe')]
    public int $roleId;

    public ?bool $toGainMuscle = false;
    public ?bool $toLoseWeight = false;
    public ?bool $toMaintainWeight = false;
    public ?bool $toImprovePhysicalHealth = false;
    public ?bool $toImproveMentalHealth = false;
    public ?bool $fixShoulder = false;
    public ?bool $fixKnees = false;
    public ?bool $fixBack = false;
    public ?bool $rehab = false;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService,
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

        $userCheckByEmail = $this->userService->getUserByEmail($this->email);

        if($userCheckByEmail)
        {
            $this->addError('email', 'Ya existe un usuario con el email introducido');
            $this->resolveRequest();
        }
    }
}