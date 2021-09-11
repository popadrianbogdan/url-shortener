<?php

namespace App\EventListener;

use App\Controller\RESTController;
use App\Exception\AuthenticationError;
use App\Exception\AuthorizationError;
use App\Pab\Data\DataProcessingError;
use App\Security\AnonymousAuthorization;
use App\Services\AuthorizationService;
use App\Services\SecurityService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ApiListener
{
    private $securityService;
    private $container;

    public function __construct(
        ContainerInterface $container,
        SecurityService $securityService
    ) {
        $this->container = $container;
        $this->securityService = $securityService;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->headers->has(RESTController::AUTHORIZATION_HEADER)) {
            $sessionId = $request->headers->get(RESTController::AUTHORIZATION_HEADER);
        } else {
            $sessionId = null;
        }

        if($sessionId) {
            $result = $this->securityService->resumeSession($sessionId);
            $request->attributes->set(RESTController::SESSION_ATTRIBUTE_KEY, $result->getSession());
            $request->attributes->set(
                AuthorizationService::AUTHORIZATION_ATTRIBUTE_KEY, $authorization = $result->getAuthorization()
            );
        } else {
            $request->attributes->set(
                AuthorizationService::AUTHORIZATION_ATTRIBUTE_KEY, $authorization =  new AnonymousAuthorization()
            );
        }

        $this->container->set('app.authorization', $authorization);
    }

    /**
     * @throws \Throwable
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        switch (true) {
            case $exception instanceof AuthorizationError:
                $event->setResponse(new JsonResponse([
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ], Response::HTTP_FORBIDDEN));
                break;
            case $exception instanceof AuthenticationError:
                $event->setResponse(new JsonResponse([
                    'error' => [
                        'message' => $exception->getMessage(),
                    ],
                ], Response::HTTP_UNAUTHORIZED));
                break;
            case $exception instanceof DataProcessingError:
                $event->setResponse(new JsonResponse([
                    'error' => [
                        'type' => 'input_error',
                        'path' => $exception->getPath(),
                        'message' => $exception->getErrorMessage(),
                    ],
                ], JsonResponse::HTTP_CONFLICT));
                break;
            default:
                throw $exception;
        }
    }
}