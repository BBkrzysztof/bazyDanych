<?php

namespace Security\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Security\Entity\Token;
use Security\Service\JwtService;
use Security\Service\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtRequestListener
{
    private Security $security;
    private JwtService $jwtService;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Security               $security,
        JwtService             $jwtService,
        EntityManagerInterface $entityManager
    )
    {
        $this->jwtService = $jwtService;
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        $token = $request->headers->get('authorization');

        if (!$token) {
            return;
        }

        [$method, $token] = explode(' ', $token);

        if ($method !== 'Bearer') {
            throw new BadRequestHttpException('Auth methode is not supported');
        }

        $data = $this->jwtService->validateJWT($token);

        if (!$data) {
            throw new UnauthorizedHttpException('', 'Invalid jwt token');
        }

        /** @var Token $token */
        $token = $this->entityManager->getRepository(Token::class)->findOneBy(['id' => $data['tokenId']]);

        if (!$token) {
            throw new UnauthorizedHttpException('', 'Invalid jwt token');
        }

        if (time() > $token->getExpiredAt()->getTimestamp()) {
            $this->entityManager->remove($token);
            $this->entityManager->flush();

            throw new UnauthorizedHttpException('', 'Jwt token expired');
        }

        $this->security->setToken($token);
        $this->security->setUser($token->getUser());
    }
}