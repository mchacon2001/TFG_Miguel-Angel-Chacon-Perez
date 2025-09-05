<?php

namespace App\Request\User;

use App\Utils\Classes\AbstractRequest;
use App\Utils\Validator\BaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class CreateReportRequest extends BaseRequest
{
    #[Assert\NotBlank(message: 'El ID del usuario es obligatorio')]
    #[Assert\Type('string', message: 'El ID del usuario debe ser una cadena')]
    public string $userId;

    #[Assert\NotBlank(message: 'El período es obligatorio')]
    #[Assert\Choice(choices: ['weekly', 'monthly', 'yearly'], message: 'El período debe ser weekly, monthly o yearly')]
    public string $period;
}
