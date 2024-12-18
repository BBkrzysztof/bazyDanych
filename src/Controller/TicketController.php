<?php

namespace App\Controller;

use App\Controller\BaseController\BaseController;
use App\Entity\Ticket;
use App\Exception\JsonBadRequestException;
use App\Paginator\Paginator;
use Security\Enum\UserRolesEnum;
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
 * @Route("/api/ticket")
 */
class TicketController extends BaseController
{
    /**
     * @Pagination
     * @Route("/", methods={"GET"})
     */
    public function getAction(Paginator $paginator): JsonResponse
    {
        return $paginator->paginate(Ticket::class);
    }

    /**
     * @LoggerAnnotation(action="created")
     * @Authenticated
     * @RequiredFields(fields={"title", "content", "tags"})
     * @Route("/", methods={"POST"})
     */
    public function createAction(Request $request): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entitySerializer->deserialize(
            $request,
            Ticket::class
        );

        $ticket->setAuthor($this->security->getUser());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return new JsonResponse($ticket, Response::HTTP_CREATED);
    }

    /**
     * @LoggerAnnotation(action="updated")
     * @Authenticated
     * @RequiredFields(fields={"title", "content", "tags"})
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, $id): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entitySerializer->deserialize(
            $request,
            Ticket::class,
            $id
        );

        if (
            !$this->security->isAdmin()
            && $ticket->getAuthor()->getId() !== $this->security->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException();
        }

        $ticket->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return new JsonResponse($ticket, Response::HTTP_OK);
    }

    /**
     * @LoggerAnnotation(action="deleted")
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction($id): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entityManager->getRepository(Ticket::class)
            ->findOneBy(['id' => $id]);

        if (!$ticket) {
            throw new NotFoundHttpException();
        }

        $ticket->setDeletedAt(new \DateTime());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @LoggerAnnotation(action="statusChange")
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin", "RoleEmployee"})
     * @RequiredFields(fields={"status"})
     * @Route("/status-change/{id}", methods={"PATCH"})
     */
    public function statusChangeAction(Request $request, $id): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entitySerializer->deserialize(
            $request,
            Ticket::class,
            $id
        );

        $errors = $this->validator->validate($ticket, ['status-change']);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $ticket->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return new JsonResponse($ticket, Response::HTTP_OK);
    }

    /**
     * @LoggerAnnotation(action="assigned")
     * @Authenticated
     * @RequiredFields(fields={"worker"})
     * @RoleGuard(roles={"RoleAdmin"})
     * @Route("/assign/{id}", methods={"PATCH"})
     */
    public function assignAction(Request $request, $id): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entitySerializer->deserialize(
            $request,
            Ticket::class,
            $id
        );

        $errors = $this->validator->validate($ticket, ['worker-assign']);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $ticket->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        return new JsonResponse($ticket, Response::HTTP_OK);
    }

}