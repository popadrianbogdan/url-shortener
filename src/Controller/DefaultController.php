<?php

namespace App\Controller;

use App\Entity\Url;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const URL_REDIRECT = 'Url-Redirect';

    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction(): Response
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("{url}")
     */
    public function urlRedirectAction(Request $request): RedirectResponse
    {
        $shortUrl = $request->get('url');

        /** @var Url $url */
        $url = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortUrl' => $shortUrl]);

        $response = new RedirectResponse($url->getLongUrl());
        $response->headers->set(self::URL_REDIRECT, $url->getId());

        return $response;
    }
}
