<?php

namespace App\EventListener;


use App\Attribute\Permission;
use App\Utils\Tools\APIJsonResponse;
use Doctrine\Common\Annotations\Reader;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use ReflectionAttribute;
use ReflectionException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class PermissionListener
{


    public function __construct(
        protected Reader $reader,
        protected Security $token,
        protected JWTTokenManagerInterface $jwtManager
    )
    {

    }

    /**
     * @param ControllerEvent $event
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!is_array($controllers = $event->getController())) {
            return ;
        }

        list($controller, $methodName) = $controllers;

        $reflectionObject = new \ReflectionObject($controller);
        $reflectionMethod = $reflectionObject->getMethod($methodName);

        $permissionsAttributeArray = $reflectionMethod->getAttributes(Permission::class);

        /** @var ReflectionAttribute $permissionAttribute */
        foreach ($permissionsAttributeArray as $permissionAttribute) {
            $group = $permissionAttribute->getArguments()['group'];
            $action = $permissionAttribute->getArguments()['action'];

            $this->can($event, $action, $group);
        }

    }

    public function can(ControllerEvent $event, string $action, string $group): void
    {
        $permissions = [];
        try {
            $tokenPayload = $this->jwtManager->decode($this->token->getToken());
            $permissions = $tokenPayload['permissions'];
        } catch (JWTDecodeFailureException $e) {

        }

        if ($permissions && @$permissions[$group] && @in_array($action, $permissions[$group])) {
            return ;
        }

        $event->setController(function(){
            return new APIJsonResponse(null, false, "No tienes acceso a este recurso", Response::HTTP_FORBIDDEN);
        });

    }
}