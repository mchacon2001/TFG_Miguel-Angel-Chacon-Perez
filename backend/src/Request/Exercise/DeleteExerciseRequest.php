<?php

namespace App\Request\Exercise;

use App\Entity\Exercise\Exercise;
use App\Services\Exercise\ExerciseService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DeleteExerciseRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: Exercise::class, message: 'El ejercicio introducido no existe en la base de datos')]
    public string $exerciseId;

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected ExerciseService $exerciseService)
    {
        parent::__construct($validator, $token, $jwtManager);
    }


    // -----------------------------------------------------------------
    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    // -----------------------------------------------------------------
    public function validate(): void
    {
        parent::validate();

        /**
         * @var \App\Entity\User\User $user
         */
        $user = $this->getUser();

        $exercise = $this->exerciseService->getExerciseByIdSimple($this->exerciseId);

        if(!$exercise)
        {
            $this->addError("exerciseId", "El ejercicio no existe");
            $this->resolveRequest();
        }

        if(!$user->isSuperAdmin() && !$user->isAdmin())
        {
        if($user->getId() !== $exercise->getUser()->getId())
            {
                $this->addError("exerciseId", "No tienes permisos para eliminar este ejercicio");
                $this->resolveRequest();
            }

            if($user->getId() !== $exercise->getUser()->getId())
            {
                $this->addError("exerciseId", "No tienes permisos para eliminar este ejercicio");
                $this->resolveRequest();
             }
        }

        
    }
    // -----------------------------------------------------------------

}