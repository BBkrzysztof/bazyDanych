<?php

namespace App\Controller;

use App\Controller\BaseController\BaseController;
use App\Entity\Tag;
use App\Paginator\Paginator;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Paginator\Annotation\Pagination;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;
use Security\Annotation\RequiredFields;

/**
 * @Route("/api/tag")
 */
class TagController extends BaseController
{
    /**
     * @Pagination
     * @Route("/", methods={"GET"})
     */
    public function getAction(Paginator $paginator): JsonResponse
    {
        return $paginator->paginate(Tag::class);
    }

    /**
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={"name"})
     * @Route("/", methods={"POST"})
     */
    public function createAction(Request $request): JsonResponse
    {
        $tag = $this->entitySerializer->deserialize(
            $request,
            Tag::class,
        );

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return new JsonResponse(
            $tag,
            Response::HTTP_CREATED
        );
    }

    /**
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={"name"})
     * @Route("/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, $id): JsonResponse
    {
        /** @var Tag $tag */
        $tag = $this->entitySerializer->deserialize(
            $request,
            Tag::class,
            $id
        );

        $this->entityManager->persist($tag);
        $this->entityManager->flush();

        return new JsonResponse(
            $tag,
            Response::HTTP_OK
        );
    }

    /**
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={})
     * @Route("/{id}", methods={"DELETE"})
     */
    public function deleteAction($id): JsonResponse
    {
        /** @var Tag $tag */
        $tag = $this->entityManager->getRepository(Tag::class)
            ->findOneBy(['id' => $id]);

        if (!$tag) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($tag);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_CREATED);
    }
}