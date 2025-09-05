<?php

namespace App\Utils\Classes;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTHandlerService
{
    public function __construct(
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager
    ) {}

    /**
     * Obtiene los datos decodificados del token JWT.
     */
    protected function getTokenPayload(): array
    {
        return $this->jwtManager->decode($this->token->getToken()) ?? [];
    }

    /**
     * Verifica si el usuario autenticado es un superadmin.
     */
    protected function isSuperAdmin(): bool
    {
        $user = $this->getUser();
        if ($user && method_exists($user, 'isSuperAdmin')) {
            return $user->isSuperAdmin();
        }

        return false;
    }

    /**
     * Obtiene el usuario autenticado.
     */
    protected function getUser(): ?UserInterface
    {
        return $this->token->getUser();
    }
}