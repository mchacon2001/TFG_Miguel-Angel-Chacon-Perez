<?php

namespace App\Security;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {

            if($user === false)
            {
                throw new CustomUserMessageAccountStatusException('Cuenta de usuario no encontrada',[], 401);
            }
    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}