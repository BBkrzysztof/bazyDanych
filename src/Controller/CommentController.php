<?php

namespace App\Controller;

use App\Controller\BaseController\BaseController;
use App\Entity\Comment;
use App\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Paginator\Annotation\Pagination;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;
use Security\Annotation\RequiredFields;

/**
 * @Route("/api/comment")
 */
class CommentController extends BaseController
{
    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function getAction($id): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entityManager->getRepository(Ticket::class)
            ->findOneBy(['id' => $id]);

        if (!$ticket) {
            throw new EntityNotFoundException();
        }

        return new JsonResponse(
            $ticket->getComments(),
            Response::HTTP_OK
        );
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={"content"})
     * @Route("/{ticketId}", methods={"POST"})
     */
    public function createAction(Request $request, $ticketId): JsonResponse
    {
        /** @var Ticket $ticket */
        $ticket = $this->entityManager->getRepository(Ticket::class)
            ->findOneBy(['id' => $ticketId]);

        if (!$ticket) {
            throw new EntityNotFoundException();
        }

        /** @var Comment $comment */
        $comment = $this->entitySerializer->deserialize(
            $request,
            Comment::class
        );

        $comment->setAuthor($this->security->getUser());
        $comment->setTicket($ticket);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new JsonResponse($comment, Response::HTTP_CREATED);
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={"content"})
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, $id): JsonResponse
    {
        /** @var Comment $comment */
        $comment = $this->entitySerializer->deserialize(
            $request,
            Comment::class,
            $id
        );

        if (
            $this->security->isRoleUser() &&
            $comment->getAuthor()->getId() !== $this->security->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException();
        }

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return new JsonResponse($comment, Response::HTTP_CREATED);
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={})
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction($id): JsonResponse
    {
        /** @var Comment $comment */
        $comment = $this->entityManager->getRepository(Comment::class)
            ->findOneBy(['id' => $id]);

        if (!$comment) {
            throw new EntityNotFoundException();
        }

        if (
            $this->security->isRoleUser() &&
            $comment->getAuthor()->getId() !== $this->security->getUser()->getId()
        ) {
            throw new AccessDeniedHttpException();
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}