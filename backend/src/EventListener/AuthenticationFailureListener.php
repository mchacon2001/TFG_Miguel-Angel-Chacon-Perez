<?php

namespace App\EventListener;

use App\Utils\Tools\APIJsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener
{
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        $response = new APIJsonResponse([], false, "Credenciales incorrectas, compruebe que su nombre de usuario y contraseña son correctos",Response::HTTP_OK);
        $event->setResponse($response);
    }
}