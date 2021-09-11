<?php

namespace App\Controller\Api;

use App\Controller\RESTController;
use App\Services\SecurityService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sessions")
 */
class SecurityController extends RESTController
{
    /**
     * @Route("", name="create_session", methods={"POST"})
     *
     * @param SecurityService $securityService
     * @return Response
     */
    public function loginAction(SecurityService $securityService): Response
    {
        $data = $this->getRequestData();
        $email = $data->access('email')->getString();
        $password = $data->access('password')->getString();

        $authenticationResult = $securityService->login($this->getAuthorization(), $email, $password);

        return $this->json($authenticationResult);
    }

    /**
     * @Route("", name="delete_session", methods={"DELETE"})
     *
     * @return Response
     */
    public function logoutAction(SecurityService $securityService)
    {
        $this->requiresAuthentication();

        $securityService->logout($this->getAuthorization(), $this->getSession()->getId());

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/me", name="get_me", methods={"GET"})
     *
     * @param SecurityService $securityService
     * @return JsonResponse
     */
    public function getMeAction(SecurityService $securityService): JsonResponse
    {
        $this->requiresAuthentication();

        return $this->json($securityService->getUserBySession($this->getSession()));
    }
}
