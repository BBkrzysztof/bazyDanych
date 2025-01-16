<?php

namespace App\Controller;

use App\Controller\BaseController\BaseController;
use App\Entity\WorkTime;
use App\Exception\JsonBadRequestException;
use App\Paginator\Paginator;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Paginator\Annotation\Pagination;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;
use Security\Annotation\RequiredFields;
use App\Logger\LoggerAnnotation;

/**
 * @Route("/api/work-time")
 */
class WorkTimeController extends BaseController
{
    /**
     * @Authenticated
     * @Pagination(likeFilters={"id"}, eqFilters={"ticket", "employee", "time", "date"})
     * @RoleGuard(roles={"RoleAdmin", "RoleEmployee"})
     * @Route("/", methods={"GET"})
     */
    public function getAction(Paginator $paginator): JsonResponse
    {

        if (!$this->security->isAdmin()) {
            [$countQuery, $entityQuery] = $paginator->setupPaginate(WorkTime::class);

            $countQuery = $countQuery->andWhere('e.employee = :param')
                ->setParameter('param', $this->security->getUser()->getId());

            $entityQuery = $entityQuery->andWhere('e.employee = :param')
                ->setParameter('param', $this->security->getUser()->getId());

            return $paginator->paginate(
                WorkTime::class,
                $countQuery,
                $entityQuery
            );
        }
        return $paginator->paginate(WorkTime::class);
    }

    /**
     * @LoggerAnnotation(action="created")
     * @Authenticated
     * @RequiredFields(fields={"time", "ticket", "createdAt"})
     * @RoleGuard(roles={"RoleAdmin","RoleEmployee"})
     * @Route("/", methods={"POST"})
     */
    public function createAction(Request $request): JsonResponse
    {
        /** @var WorkTime $workTime */
        $workTime = $this->entitySerializer->deserialize(
            $request,
            WorkTime::class
        );
        $workTime->setEmployee($this->security->getUser());

        $errors = $this->validator->validate($workTime);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $this->entityManager->persist($workTime);
        $this->entityManager->flush();

        return new JsonResponse($workTime, Response::HTTP_CREATED);
    }

    /**
     * @LoggerAnnotation(action="updated")
     * @Authenticated
     * @RequiredFields(fields={"time", "ticket", "createdAt"})
     * @RoleGuard(roles={"RoleAdmin", "RoleEmployee"})
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, $id): JsonResponse
    {
        /** @var WorkTime $workTime */
        $workTime = $this->entitySerializer->deserialize(
            $request,
            WorkTime::class,
            $id
        );

        if (
            !$this->security->isAdmin()
            && $workTime->getEmployee()->getId() !== $this->security->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException("You dont have permissions");
        }

        $errors = $this->validator->validate($workTime);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $this->entityManager->persist($workTime);
        $this->entityManager->flush();
        return new JsonResponse($workTime, Response::HTTP_OK);
    }

    /**
     * @LoggerAnnotation(action="deleted")
     * @Authenticated
     * @RequiredFields(fields={})
     * @RoleGuard(roles={"RoleAdmin","RoleEmployee"})
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction($id): JsonResponse
    {
        /** @var WorkTime $workTime */
        $workTime = $this->entityManager->getRepository(WorkTime::class)
            ->findOneBy(['id' => $id]);

        if (!$workTime) {
            throw new NotFoundHttpException();
        }

        if (
            !$this->security->isAdmin()
            && $workTime->getEmployee()->getId() !== $this->security->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException("You dont have permissions");
        }

        $workTime->setDeletedAt(new \DateTime());

        $this->entityManager->persist($workTime);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

}