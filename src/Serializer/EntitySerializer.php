<?php

namespace App\Serializer;

use App\Exception\JsonBadRequestException;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\ORM\EntityManagerInterface;
use Security\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntitySerializer
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deserialize(Request $request, string $entityClass, ?string $id = null): mixed
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('Invalid JSON data.');
        }

        $entity = new $entityClass();

        if ($id) {
            $entity = $this->fetchEntity($id);

            if (!$entity) {
                throw new NotFoundHttpException();
            }
        }

        $errors = [];

        foreach ($data as $key => $value) {
            $setterMethod = 'set' . ucfirst($key);
            if (method_exists($entity, $setterMethod)) {
                $entity->$setterMethod($value);
            } else {
                $errors[$key] = 'Field dont exists';
            }
        }

        if (!empty($errors)) {
            throw new JsonBadRequestException($errors);
        }

        return $entity;
    }

    private function fetchEntity(string $id): mixed
    {
        try {
            return $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        } catch (ConversionException $exception) {
            // if api gets invalid uu id form return 404
            throw new NotFoundHttpException();
        }
    }
}