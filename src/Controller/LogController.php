<?php

namespace App\Controller;

use App\Controller\BaseController\BaseController;
use App\Entity\Log;
use App\Paginator\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Paginator\Annotation\Pagination;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;
use Security\Annotation\RequiredFields;

/**
 * @Route("/api/log")
 */
class LogController extends BaseController
{

    /**
     * @Pagination(likeFilters={"id","action"}, eqFilters={"user","ticket"})
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @Route("/", methods={"GET"})
     */
    public function getAction(Paginator $paginator): JsonResponse
    {
        return $paginator->paginate(Log::class);
    }
}