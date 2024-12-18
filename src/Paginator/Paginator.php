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
    private int $total = 1;

    private EntityManagerInterface $entityManager;

    private array $likeFilters = [];
    private array $eqFilters = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setPagination(int $current, int $limit = 25): void
    {
        $this->current = $current;
        $this->limit = $limit;
    }

    /**
     * @param array $likeFilters
     */
    public function setLikeFilters(array $likeFilters): void
    {
        $this->likeFilters = $likeFilters;
    }

    /**
     * @param array $eqFilters
     */
    public function setEqFilters(array $eqFilters): void
    {
        $this->eqFilters = $eqFilters;
    }


    public function setupPaginate(string $entity): array
    {
        $countQuery = $this->createQueryBuilder()
            ->select('count(e)')
            ->from($entity, 'e');

        $entityQuery = $this->createQueryBuilder()
            ->select('e')
            ->from($entity, 'e');

        return [$countQuery, $entityQuery];
    }

    public function paginate(
        string        $entity,
        ?QueryBuilder $countQuery = null,
        ?QueryBuilder $entityQuery = null,
    ): JsonResponse
    {
        if (!$countQuery) {
            [$countQuery,] = $this->setupPaginate($entity);
        }

        if (!$entityQuery) {
            [, $entityQuery] = $this->setupPaginate($entity);
        }

        [$countQuery, $entityQuery] = $this->applyFilters($countQuery, $entityQuery);

        $total = (int)$countQuery->getQuery()
            ->getSingleScalarResult();

        $this->maxPages = ceil($total / $this->limit);
        $this->total = $total;

        $data = $entityQuery->setFirstResult(($this->current - 1) * $this->limit)
            ->setMaxResults($this->limit)
            ->getQuery()->getResult();

        return $this->paginationResponse($data);
    }

    private function applyFilters(
        QueryBuilder &$countQuery,
        QueryBuilder &$entityQuery
    ): array
    {

        foreach ($this->likeFilters as $field => $value) {
            $countQuery->andWhere("e.{$field} LIKE :param_{$field}")
                ->setParameter("param_{$field}", "%$value%");


            $entityQuery->andWhere("e.{$field} LIKE :param_{$field}")
                ->setParameter("param_{$field}", "%$value%");

        }

        foreach ($this->eqFilters as $field => $value) {
            $countQuery->andWhere("e.{$field} = :param_{$field}")
                ->setParameter("param_{$field}", "$value");

            $entityQuery->andWhere("e.{$field} = :param_{$field}")
                ->setParameter("param_{$field}", "$value");

        }


        return [$countQuery, $entityQuery];
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
            'total' => $this->total,
            'pages' => $this->maxPages,
            'limit' => $this->limit
        ], Response::HTTP_OK);
    }

}