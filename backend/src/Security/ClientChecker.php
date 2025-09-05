<?php

namespace App\Security;

use App\Entity\Client\Client;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
            if($user && $user->isActive() === false)
            {
                throw new CustomUserMessageAccountStatusException('Your client account is inactive.');
            }

    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}