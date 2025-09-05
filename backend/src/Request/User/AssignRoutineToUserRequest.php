<?php

namespace App\Request\User;

use App\Entity\Routine\Routine;
use App\Services\User\UserPermissionService;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use DateTime;

class AssignRoutineToUserRequest extends BaseRequest
{    
    public ?array $userIds;

    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Routine::class, message: 'La rutina seleccionada no existe')]
    public string $routineId;

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
    }
}