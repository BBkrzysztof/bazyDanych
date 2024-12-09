<?php

namespace Security\Controller;

use App\Exception\JsonBadRequestException;
use App\Serializer\EntitySerializer;
use App\Validator\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Security\Annotation\RequiredFields;
use Security\Annotation\Authenticated;
use Security\Annotation\RoleGuard;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{

    private EntitySerializer $entitySerializer;
    private Validator $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntitySerializer       $entitySerializer,
        Validator              $validator,
        EntityManagerInterface $entityManager
    )
    {
        $this->entitySerializer = $entitySerializer;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
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
     * @RoleGuard(roles={"RoleAdmin"})
     * @Route("/user/list", methods={"GET"})
     */
    public function listUser(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * @Authenticated
     * @RequiredFields(fields={})
     * @Route("/user/edit/{id}", methods={"PUT"})
     */
    public function editUser(): JsonResponse
    {
        //@todo add edit user
        return new JsonResponse();
    }

    /**
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={})
     * @Route("/user/delete/{id}", methods={"DELETE"})
     */
    public function deleteUser(): JsonResponse
    {
        //@todo add delete user
        return new JsonResponse();
    }

    /**
     * @Authenticated
     * @RoleGuard(roles={"RoleAdmin"})
     * @RequiredFields(fields={"role"}, strict=true)
     * @Route("/user/change-role/{id}", methods={"POST"})
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
}