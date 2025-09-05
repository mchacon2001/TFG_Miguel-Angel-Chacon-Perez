<?php

namespace App\Request\Public;


use App\Utils\Validator\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;


class RecoverPasswordRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $query_token;

    #[NotBlank]
    #[NotNull]
    #[Regex(
        pattern: '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}$/',
        message: 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos un número, una mayúscula y una minúscula.'
    )]
    public string $password;

    #[NotBlank]
    #[NotNull]
    public string $password_confirmation;
}