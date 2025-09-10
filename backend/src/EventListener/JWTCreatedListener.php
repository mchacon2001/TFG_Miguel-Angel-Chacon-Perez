<?php

namespace App\EventListener;

use App\Entity\User\User;
use App\Services\User\UserService;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function __construct(protected UserService $userService)
    {}

    /**
     * @throws NonUniqueResultException
     */
    public function onJWTCreated(JWTCreatedEvent $event): void
    {

        $payload       = $event->getData();

        if($event->getUser() instanceof User)
        {

            $user = $this->userService->getUserByEmail($payload['email']);

            $payload = $user->toArray();

            $payload['roles'] = $user->getRoles();
            $event->setData($payload);

            $header        = $event->getHeader();
            $header['cty'] = 'JWT';

            $event->setHeader($header);

        }

    }
}