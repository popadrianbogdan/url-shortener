<?php

namespace App\Controller\Api;

use App\ApiProcessor\UserProcessor;
use App\Controller\RESTController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/user")
 */
class UserController extends RESTController
{
    /**
     * @Route("", methods={"POST"})
     */
    public function newAction(UserProcessor $userProcessor, SerializerInterface $serializer): JsonResponse
    {
        $data = $this->getRequestData();

        $user = $data->process($userProcessor)->get();
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return JsonResponse::fromJsonString(
            $serializer->serialize($user, 'json'),
            Response::HTTP_CREATED,
            array(
                'Location' => $this->generateUrl('create_session')
            )
        );
    }
}