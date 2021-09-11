<?php

namespace App\EventListener;

use App\Controller\DefaultController;
use App\Entity\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RedirectListener
{
    private $container;

    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $request = $event->getResponse();
        $urlRedirectHeader = $request->headers->get(DefaultController::URL_REDIRECT);

        if(null === $urlRedirectHeader) {
            return;
        }

        $em = $this->container->get('doctrine.orm.entity_manager');
        $urlRepo = $em->getRepository(Url::class);

        /** @var Url $url */
        $url = $urlRepo->findOneBy(['id' => $urlRedirectHeader]);
        $url->setViews($url->getViews() + 1);

        $em->persist($url);
        $em->flush();
    }
}