<?php

namespace App\EventListener;


use App\Utils\Tools\APIJsonResponse;
use App\Utils\Exceptions\APIException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;


class ExceptionListener
{
    public function __construct(protected ParameterBagInterface $bag)
    {
    }

    public function onKernelException(ExceptionEvent $exceptionEvent)
    {
        $exception       = $exceptionEvent->getThrowable();
        $environmentMode = $this->bag->get('app.env');

        if ($exception instanceof APIException) {
            $response = new APIJsonResponse(null, false, $exception->getMessage(), $exception->getCode());
            $exceptionEvent->setResponse($response);
            return;
        }

        if ($exception instanceof HttpException and $exception->getPrevious() instanceof InsufficientAuthenticationException) {
            $response = new APIJsonResponse(null, false, $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
            $exceptionEvent->setResponse($response);
            return;
        }

        if ($exception instanceof AccessDeniedHttpException) {
            $response = new APIJsonResponse(null, false, "You haven't access to this resource", Response::HTTP_FORBIDDEN);
            $exceptionEvent->setResponse($response);
            return;
        }

        if($environmentMode == "dev" or $environmentMode == "local") {
            $response = new APIJsonResponse(data: ['trace' => $exception->getTrace()], success: false, message: $exception->getMessage(), status: Response::HTTP_INTERNAL_SERVER_ERROR);;
            $exceptionEvent->setResponse($response);
            return;
        }



    }
}