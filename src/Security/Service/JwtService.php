<?php

namespace Security\Service;

use Doctrine\ORM\EntityManagerInterface;
use Security\Entity\Token;
use Security\Entity\User;

class JwtService
{

    private string $privateKey;

    private EntityManagerInterface $entityManager;


    public function __construct(string $privateKey, EntityManagerInterface $entityManager)
    {
        $this->privateKey = $privateKey;
        $this->entityManager = $entityManager;
    }

    public function generateJwt(User $user): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $base64Header = $this->base64UrlEncode($header);
        $exp = time() + 3600;

        $expAt = (new \DateTime())->setTimestamp($exp);

        $tokenId = $this->createToken($user, $expAt);

        $payload = json_encode([
            'id' => $user->getId(),
            'tokenId' => $tokenId,
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'iat' => time(),
            'exp' => $exp
        ]);

        $base64Payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac(
            'sha256',
            "$base64Header.$base64Payload",
            $this->privateKey,
            true
        );
        $base64Signature = $this->base64UrlEncode($signature);

        return "$base64Header.$base64Payload.$base64Signature";
    }

    function validateJWT(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$base64Header, $base64Payload, $base64Signature] = $parts;

        $signature = $this->base64UrlEncode(hash_hmac(
            'sha256',
            "$base64Header.$base64Payload",
            $this->privateKey,
            true
        ));

        if (!hash_equals($base64Signature, $signature)) {
            return null;
        }

        $payload = json_decode(base64_decode($base64Payload), true);

        if (!$payload) {
            return null;
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function createToken(User $user, \DateTimeInterface $ttl): string
    {
        $token = new Token();
        $token->setUser($user);
        $token->setExpiredAt($ttl);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token->getId();
    }

}