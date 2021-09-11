<?php

namespace App\Security;

use App\Security\Value\IdentityType;

class Identity
{
    private $user;
    private $identityType;

    public function __construct(IdentityType $identityType, int $userId = null)
    {
        $this->identityType = $identityType;
        $this->user = $userId;
    }

    public function getUserId(): ?int
    {
        return $this->user;
    }

    public static function default(): Identity
    {
        return new self(IdentityType::anonymous());
    }
}