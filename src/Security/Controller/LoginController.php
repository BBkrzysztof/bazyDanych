<?php

namespace Security\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Security\Annotation\RequiredFields;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class LoginController extends AbstractController
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
     * @RequiredFields(fields={"name", "email"})
     */
    public function login(Request $request): JsonResponse
    {
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