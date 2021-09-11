<?php

namespace App\Security;

use App\Security\Value\IdentityType;
use DateTimeImmutable;

class Session
{
    private $id;
    private $user;
    private $createdAt;

    public function __construct(string $sessionId, int $userId, DateTimeImmutable $createdAt)
    {
        $this->id = $sessionId;
        $this->user = $userId;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getIdentity(): Identity
    {
        return new Identity(IdentityType::user(), $this->user);
    }
}