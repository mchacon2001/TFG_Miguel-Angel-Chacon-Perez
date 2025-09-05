<?php

namespace App\EventListener;

use App\Utils\Tools\APIJsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationInvalidResponse
{
    public function __construct(protected JWTEncoderInterface $encoder)
    {
    }

    public function onJWTInvalid(AuthenticationFailureEvent $event)
    {
        /*
        $token = str_replace("Bearer ", "", $event->getRequest()->headers->get('authorization'));
        dd($token);

        $response = new APIJsonResponse([], false, "Bad credentials, please verify that your username/password are correctly set",Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
        */
    }
}