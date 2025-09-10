<?php

namespace App\EventListener;

use App\Entity\User\User;
use App\Services\User\UserService;
use App\Utils\Tools\APIJsonResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class AuthenticationSuccessListener
{
    public function __construct(protected UserService $userService
    )
    {

    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof JWTUserInterface) {
            return;
        }

        if($user instanceof User)
        {
            $this->userService->updateLastLogin($user);
            $data['user'] = $user->toArray();
            $payload = [
                "success" => true,
                "data"    => $data,
                "message" => "OK",
                "errors"  => null,
            ];


            $event->setData($payload);
        }
    }
}