<?php


namespace App\Services\User;


use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserPermissionService
{

    public function __construct(
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager
    )
    {
    }

    /**
     * Función utilizada en la fachada para comprobar si el usuario actual tiene acceso al recursos solicitado.
     *
     * @param string $action
     * @param string $group
     * @return bool|null
     */
    public function can(string $action, string $group): ?bool
    {
        $permissions = [];
        try {
            $tokenPayload = $this->jwtManager->decode($this->token->getToken());
            $permissions = $tokenPayload['permissions'];
        } catch (JWTDecodeFailureException $e) {

        }

        if ($permissions)
        {
            return @in_array($action, $permissions[$group]);
        }
        return false;

    }

}
