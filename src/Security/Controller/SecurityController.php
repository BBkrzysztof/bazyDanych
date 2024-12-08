<?php

namespace Security\Controller;

use App\Serializer\EntitySerializer;
use Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Security\Annotation\RequiredFields;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{

    /**
     * @Route("/register", methods={"POST"})
     *
     */
    public function register(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function login(Request $request, EntitySerializer $entitySerializer): JsonResponse
    {
        $user = $entitySerializer->deserialize($request, User::class, '550e8400-e29b-41d4-a716-446655440000');
        //@todo add login logic
        return new JsonResponse([]);

    }

    /**
     * @Route("/loogut", methods={"POST"})
     */
    public function logout(Request $request): JsonResponse
    {
        //@todo add logout logic
        return new JsonResponse([]);
    }
}