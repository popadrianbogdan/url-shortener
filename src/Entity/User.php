<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * User.
 *
 * @ORM\Table(name="user", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="email_unique", columns={"email"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\EntityListeners({"App\EventListener\UserListener"})
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable="false")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=128)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->salt = md5(uniqid('', true));
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function setSalt($salt): User
    {
        $this->salt = $salt;

        return $this;
    }
}
