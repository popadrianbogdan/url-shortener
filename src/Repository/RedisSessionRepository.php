<?php

namespace App\Repository;

use App\Exception\NotFoundException;
use App\Security\Session;
use App\Security\SessionRepository;
use DateTimeImmutable;
use Predis\ClientInterface;

final class RedisSessionRepository implements SessionRepository
{
    private $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @throws \Exception
     */
    public function create(int $userId): string
    {
        $sessionId = (bin2hex(random_bytes(16)));

        $this->redis->hmset("sessions.$sessionId", [
           'userId' => $userId,
           'createdAt' => time(),
        ]);

        $this->touch($sessionId);

        return $sessionId;
    }

    public function touch(string $sessionId): void
    {
        $expiration = (new DateTimeImmutable())->modify('+1 day')->getTimestamp();
        $this->redis->expireAt("sessions.$sessionId", $expiration);
    }

    public function find(string $sessionId): Session
    {
        $data = $this->redis->hGetAll("sessions.$sessionId");

        if (!$data) {
            throw new NotFoundException();
        }

        return new Session(
            $sessionId,
            (int) $data['userId'],
            (new DateTimeImmutable())->setTimestamp($data['createdAt'])
        );
    }

    public function delete(string $sessionId): void
    {
        $this->redis->del("sessions.$sessionId");
    }
}