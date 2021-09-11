<?php

namespace App\ApiProcessor;

use App\Entity\Url;
use App\Entity\User;
use App\Pab\Data\DataHelper;
use App\Pab\Data\DataProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UrlProcessor implements DataProcessor
{
    /**
     * @var Url|null
     */
    private $url;
    private $entityManager;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container = null)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function __invoke($in)
    {
        $data = new DataHelper($in);

        $longUrl = $data->access('longUrl')->getString();


        if ($this->url) {
            $url = $this->url;
            $changedLongUrl= $data->maybe()->access('longUrl')->getString();
            $changedShortUrl = $data->maybe()->access('shortUrl')->getString();
            $changedViews  = $data->maybe()->access('views')->getInteger();

            if($changedLongUrl) {
                $url->setLongUrl($changedLongUrl);
            }

            if($changedShortUrl) {
                $url->setShortUrl($changedShortUrl);
            }

            if($changedViews) {
                $url->setViews($changedViews);
            }
        } else {
            $url = new Url();
            $url->setLongUrl($longUrl);
            $url->setShortUrl(substr(md5($longUrl), 0, 7));
            $url->setViews(0);
            $userRepo = $this->container->get('doctrine')->getRepository(User::class);
            $email = $data->access('userEmail')->getString();
            $user = $userRepo->findOneBy(['email' => $email]);
            $url->setUser($user);
        }

        return $url;
    }

    public function setUrl(Url $url)
    {
        $this->url = $url;
    }
}