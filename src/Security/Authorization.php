<?php

namespace App\Security;

Interface Authorization
{
    public function isAnonymous(): bool;

    public function canLogin(): bool;

    public function canLogout(): bool;

    public function getIdentity(): Identity;
}
