<?php


namespace App\Controller\Api;

use App\ApiProcessor\UrlProcessor;
use App\Controller\RESTController;
use App\Entity\Url;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/urls")
 */
class UrlController extends RESTController
{
    /**
     * @Route("", name="list_urls", methods={"GET"})
     *
     * @param SecurityService $securityService
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function listAction(SecurityService $securityService, SerializerInterface $serializer): JsonResponse
    {
        $this->requiresAuthentication();
        $user = $securityService->getUserBySession($this->getSession());

        $urls = $this->getDoctrine()->getRepository(Url::class)->findBy(['user' => $user->getId()]);

        return JsonResponse::fromJsonString($serializer->serialize($urls, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="show_url", requirements={"id": "\d+"}, methods={"GET"})
     *
     * @param Url $url
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function showAction(Url $url, SerializerInterface $serializer): JsonResponse
    {
        $this->requiresAuthentication();

        return JsonResponse::fromJsonString($serializer->serialize($url, 'json'), Response::HTTP_OK);
    }

    /**
     * @Route("", name="new_url", methods={"POST"})
     *
     * @param UrlProcessor $urlProcessor
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function newAction(UrlProcessor $urlProcessor, SerializerInterface $serializer): JsonResponse
    {
        $this->requiresAuthentication();

        $data = $this->getRequestData();

        $url = $data->process($urlProcessor)->get();

        $this->getDoctrine()->getManager()->persist($url);
        $this->getDoctrine()->getManager()->flush();

        return JsonResponse::fromJsonString($serializer->serialize($url, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="edit_url", methods={"PUT"})
     *
     * @param Url $url
     * @param UrlProcessor $urlProcessor
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function editAction(Url $url, UrlProcessor $urlProcessor, SerializerInterface $serializer): JsonResponse
    {
        $this->requiresAuthentication();

        $data = $this->getRequestData();

        $urlProcessor->setUrl($url);
        $data->process($urlProcessor)->get();

        $this->getDoctrine()->getManager()->flush();

        return JsonResponse::fromJsonString($serializer->serialize($url, 'json'), Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="delete_url", methods={"DELETE"})
     */
    public function deleteAction(Url $url): JsonResponse
    {
        $this->requiresAuthentication();

        $em = $this->getDoctrine()->getManager();
        $em->remove($url);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
