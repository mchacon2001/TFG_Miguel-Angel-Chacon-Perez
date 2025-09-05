<?php

namespace App\Request\Exercise;

use App\Services\Exercise\ExerciseService;
use App\Services\User\UserPermissionService;
use App\Utils\Validator\BaseRequest;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateExerciseCategoryRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $name;

    public ?string $description = null;



    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected ExerciseService $exerciseService,
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

        $checkExerciseCategoryId = $this->exerciseService->getExerciseCategoryByName($this->name);
        
        if($checkExerciseCategoryId)
        {
            $this->addError('name', 'Ya existe una categoria de ejercicios con el nombre introducido');
            $this->resolveRequest();
        }
    }

}