<?php

namespace App\Request\Public;

use App\Utils\Validator\BaseRequest;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;


class SendEmailToRecoverPasswordRequest extends BaseRequest
{
    #[NotBlank]
    #[NotNull]
    public string $email;
}