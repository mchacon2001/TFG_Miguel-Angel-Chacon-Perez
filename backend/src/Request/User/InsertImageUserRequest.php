<?php

namespace App\Request\User;


use App\Entity\User\User;
use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use App\Utils\Validator\CustomValidators\Constraints\ConstraintExistsInDatabase;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InsertImageUserRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    #[Uuid]
    #[ConstraintExistsInDatabase(field: 'id', entityClass: User::class, message: "El usuario introducido no existe en la base de datos")]
    public string $userId;

    #[File(mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'], mimeTypesMessage: "El archivo no es una imagen válida.")]
    public ?UploadedFile $profileImg;

    protected function includeFiles(): bool
    {
        return true;
    }

    public function __construct(ValidatorInterface $validator,
                                Security $token,
                                JWTTokenManagerInterface $jwtManager,
                                protected UserService $userService)
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

        $user = $this->userService->getUserByIdSimple($this->userId);

        if( !$user) {
            $this->addError("userId", "El usuario no existe");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------
}