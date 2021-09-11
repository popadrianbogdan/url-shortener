<?php

namespace App\Security;

use App\Entity\User;

class AuthenticationResult
{
    private $authorization;
    private $user;
    private $session;

    public function __construct(Authorization $authorization, User $user, Session $session)
    {
        $this->authorization = $authorization;
        $this->user = $user;
        $this->session = $session;
    }

    public function getAuthorization(): Authorization
    {
        return $this->authorization;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
