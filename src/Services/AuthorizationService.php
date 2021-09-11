<?php

namespace App\Services;

use App\Security\Authorization;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthorizationService
{
    const AUTHORIZATION_ATTRIBUTE_KEY = 'url-shortener.authorization';

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCurrentAuthorization(): ?Authorization
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (!$currentRequest) {
            return null;
        }

        return $currentRequest->attributes->get(self::AUTHORIZATION_ATTRIBUTE_KEY);
    }
}
