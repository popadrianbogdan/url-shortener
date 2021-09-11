<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Url.
 *
 * @ORM\Table(name="url", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="short_url_unique", columns={"short_url"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 */
class Url
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
     * @ORM\Column(name="long_url", type="string", length=255, nullable=false)
     */
    private $longUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="short_url", type="string", length=7)
     */
    private $shortUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="views", type="integer")
     */
    private $views;


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
        $this->createdAt = new DateTime();
    }

     function getId(): int
     {
        return $this->id;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): Url
    {
        $this->shortUrl = $shortUrl;

        return $this;
    }

    public function getLongUrl(): string
    {
        return $this->longUrl;
    }

    public function setLongUrl(string $longUrl): Url
    {
        $this->longUrl = $longUrl;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;
    }


    public function getViews(): int
    {
        return $this->views;
    }


    public function setViews(int $views): Url
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
