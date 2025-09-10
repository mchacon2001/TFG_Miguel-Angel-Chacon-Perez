<?php

namespace App\Request\Public;


use App\Services\User\UserService;
use App\Utils\Validator\BaseRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploadFileToServerRequest extends BaseRequest
{
    public ?UploadedFile $file;

    public ?string $apiKey = null;

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
    public function validate(): void
    {
        parent::validate();

        if(!$this->apiKey)
        {
            $this->addError("", "Error de validación");
            $this->resolveRequest();
        }

        if(!$this->file)
        {
            $this->addError("file", "El archivo es obligatorio");
            $this->resolveRequest();
        }
    }
    // -----------------------------------------------------------------
}