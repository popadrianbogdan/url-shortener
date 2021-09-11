<?php

namespace App\Security;

final class AnonymousAuthorization implements Authorization
{
    public function isAnonymous(): bool
    {
        return true;
    }

    public function canLogin(): bool
    {
        return true;
    }

    public function canLogout(): bool
    {
        return false;
    }

    public function getIdentity(): Identity
    {
        return Identity::default();
    }
}