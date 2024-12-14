<?php

namespace App\Paginator;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Paginator
{
    private int $current = 1;
    private int $maxPages = 1;
    private int $limit = 25;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setPagination(int $current, int $limit = 25): void
    {
        $this->current = $current;
        $this->limit = $limit;
    }

    public function paginate(string $entity): JsonResponse
    {
        $total = (int)$this->createQueryBuilder()
            ->select('count(e.id)')
            ->from($entity, 'e')
            ->getQuery()
            ->getSingleScalarResult();

        $this->maxPages = ceil($total / $this->limit);
        $data = $this->createQueryBuilder()->select('e')
            ->from($entity, 'e')
            ->setFirstResult(($this->current - 1) * $this->limit)
            ->setMaxResults($this->limit)
            ->getQuery()->getResult();

        return $this->paginationResponse($data);
    }


    private function createQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }

    private function paginationResponse(mixed $data): JsonResponse
    {
        return new JsonResponse([
            'data' => $data,
            'current_page' => $this->current,
            'pages' => $this->maxPages,
            'limit' => $this->limit
        ], Response::HTTP_OK);
    }

}