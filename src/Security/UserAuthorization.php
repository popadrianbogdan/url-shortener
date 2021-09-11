<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Value\IdentityType;

final class UserAuthorization implements Authorization
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function isAnonymous(): bool
    {
        return false;
    }

    public function canLogin(): bool
    {
        return true;
    }

    public function canLogout(): bool
    {
        return true;
    }

    public function getIdentity(): Identity
    {
        return new Identity(IdentityType::user(), $this->user->getId());
    }
}
