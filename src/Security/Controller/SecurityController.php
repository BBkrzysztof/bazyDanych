<?php

namespace Security\Controller;

use Security\Service\JwtService;
use App\Serializer\EntitySerializer;
use Doctrine\ORM\EntityManagerInterface;
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
    private const ACCOUNT_DOES_NOT_EXIST = [
        'data' => 'User with this email or password does not exists'
    ];

    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * @Route("/register", methods={"POST"})
     */
    public function register(
        Request                $request,
        EntitySerializer       $entitySerializer,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        //@todo add validation
        /** @var User $user */
        $user = $entitySerializer->deserialize($request, User::class);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/login", methods={"POST"})
     * @RequiredFields(fields={"email","password"})
     */
    public function login(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $data = $request->request->all();

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        if (!$user) {
            return new JsonResponse(
                self::ACCOUNT_DOES_NOT_EXIST,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            return new JsonResponse(
                self::ACCOUNT_DOES_NOT_EXIST,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $jwt = $this->jwtService->generateJwt($user);

        return new JsonResponse([
            'jwt' => $jwt,
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ]);
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