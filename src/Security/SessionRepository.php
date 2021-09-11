<?php

namespace App\Security;

interface SessionRepository
{
    public function create(int $userId): string;

    public function touch(string $sessionId): void;

    public function find(string $sessionId): Session;

    public function delete(string $sessionId): void;
}