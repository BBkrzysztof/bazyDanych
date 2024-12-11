<?php

namespace Security\Controller;

use Security\Service\JwtService;
use Doctrine\ORM\EntityManagerInterface;
use Security\Entity\User;
use Security\Service\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Security\Annotation\RequiredFields;
use Security\Annotation\Authenticated;
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
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        JwtService             $jwtService,
        EntityManagerInterface $entityManager,
        Security               $security
    )
    {
        $this->jwtService = $jwtService;
        $this->entityManager = $entityManager;
        $this->security = $security;
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
     * @Authenticated
     * @Route("/logout", methods={"POST"})
     */
    public function logout(
        Request                $request,
        Security               $security,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $token = $security->getToken();

        $entityManager->remove($token);
        $entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Authenticated
     * @Route("/reset-password", methods={"POST"})
     */
    public function resetPasswordRequest(Security $security): JsonResponse
    {
        $token = base64_encode(time());

        $user = $security->getUser();

        $user->setResetPasswordToken($token);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'token' => $token
        ]);
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={"password"})
     * @Route("/reset-password/{token}", methods={"POST"})
     */
    public function resetPassword(Request $request, $token): JsonResponse
    {
        $password = $request->request->get('password');
        $user = $this->security->getUser();

        if (!$user->getResetPasswordToken() || $user->getResetPasswordToken() !== $token) {
            throw new BadRequestException('Invalid reset password token');
        }

        $user->setPassword($password);
        $user->setResetPasswordToken('');
        foreach ($user->getTokens() as $token) {
            $this->entityManager->remove($token);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}