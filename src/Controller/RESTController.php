<?php

namespace App\Controller;

use App\Exception\AuthenticationError;
use App\Exception\AuthorizationError;
use App\Pab\Data\DataHelper;
use App\Pab\Util\Json;
use App\Security\Authorization;
use App\Security\Session;
use App\Services\AuthorizationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RESTController extends AbstractController
{
    const SESSION_ATTRIBUTE_KEY = 'url-shortener.session';
    const AUTHORIZATION_HEADER = 'X-Auth';

    public static function getSubscribedServices(): array
    {
        $services =  parent::getSubscribedServices();
        $services['authService'] = AuthorizationService::class;

        return $services;
    }

    protected function getAuthorization(): Authorization
    {
        return $this->get('authService')->getCurrentAuthorization();
    }

    protected function getSession(): Session
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if (!$request->attributes->has(self::SESSION_ATTRIBUTE_KEY)) {
            throw new AuthorizationError('No session exists.');
        }

        return $request->attributes->get(self::SESSION_ATTRIBUTE_KEY);
    }

    protected function getRequestData(): DataHelper
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if (!preg_match('#^application/json(;.*)?$#', $request->headers->get('Content-Type'))) {
            return new DataHelper($request->request->all());
        }

        return new DataHelper(Json::decode($request->getContent()));
    }

    protected function requiresAuthentication()
    {
        if ($this->getAuthorization()->isAnonymous()) {
            throw new AuthenticationError('Anonymous user');
        }
    }
}
