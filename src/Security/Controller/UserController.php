<?php

namespace Security\Controller;

use App\Controller\BaseController\BaseController;
use App\Exception\JsonBadRequestException;

use App\Paginator\Paginator;
use Security\Entity\User;
use Security\Enum\UserRolesEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Security\Annotation\RequiredFields;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;
use App\Paginator\Annotation\Pagination;
use App\Logger\LoggerAnnotation;

/**
 * @Route("/api")
 */
class UserController extends BaseController
{

    /**
     * @RequiredFields(fields={"email", "password"})
     * @Route("/register", methods={"POST"})
     * @throws \ReflectionException
     */
    public function register(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->entitySerializer->deserialize($request, User::class);

        $errors = $this->validator->validate($user, ['create']);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ], Response::HTTP_CREATED);
    }

    /**
     * @Authenticated
     * @Pagination
     * @RoleGuard(roles={"RoleAdmin"})
     * @Route("/user", methods={"GET"})
     */
    public function listUser(Paginator $paginator): JsonResponse
    {
        return $paginator->paginate(User::class);
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={"email"})
     * @Route("/user/{userId}", methods={"PATCH"})
     */
    public function updateUser(Request $request): JsonResponse
    {
        $id = $request->get('userId');

        $updatedUser = $this->entitySerializer->deserialize($request, User::class, $id);
        $this->validateIfActionIsPerformedOnMe($id);

        $errors = $this->validator->validate($updatedUser, ['update']);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $this->entityManager->persist($updatedUser);
        $this->entityManager->flush();

        return new JsonResponse($updatedUser);
    }

    /**
     * @LoggerAnnotation(action="userDeleted")
     * @Authenticated
     * @RequiredFields(fields={})
     * @Route("/user/{id}", methods={"DELETE"})
     */
    public function deleteUser($id): JsonResponse
    {
        $this->validateIfActionIsPerformedOnMe($id);

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        $user->setDeletedAt(new \DateTime());

        foreach ($user->getTokens() as $token) {
            $this->entityManager->remove($token);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @LoggerAnnotation(action="roleSet")
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={"role"})
     * @Route("/user/change-role/{id}", methods={"PATCH"})
     */
    public function changeRole(Request $request, $id): JsonResponse
    {
        $updatedUser = $this->entitySerializer->deserialize($request, User::class, $id);


        $errors = $this->validator->validate($updatedUser, ['update-role']);

        if ($errors) {
            throw new JsonBadRequestException($errors);
        }

        $this->entityManager->persist($updatedUser);
        $this->entityManager->flush();

        return new JsonResponse(['data' => 'Role updated']);
    }

    /**
     * check if user is performing action on himself, admin can perform action on other
     * @param string $id
     * @return void
     */
    private function validateIfActionIsPerformedOnMe(string $id): void
    {
        if ($this->security->getUser()->getId() === $id) {
            return; //action performed on himself
        }

        if ($this->security->isAdmin()) {
            return; //action performed by admin
        }

        throw new AccessDeniedHttpException();
    }
}