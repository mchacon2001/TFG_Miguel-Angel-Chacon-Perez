<?php

namespace App\Utils\Exceptions;

final class APIException extends \Exception
{
    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, $code);
    }
}