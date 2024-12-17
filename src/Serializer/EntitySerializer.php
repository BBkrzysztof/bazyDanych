<?php

namespace App\Serializer;

use App\Exception\JsonBadRequestException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\Proxy;
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

        $errors = [];

        $entity = $id ? $this->fetchEntity($id, $entityClass) : new $entityClass();

        if ($id && !$entity) {
            throw new NotFoundHttpException();
        }

        $classMetadata = $this->entityManager->getClassMetadata($entityClass);

        foreach ($data as $key => $value) {
            $setterMethod = 'set' . ucfirst($key);

            if (!method_exists($entity, $setterMethod)) {
                $errors[$key] = 'Field dont exists';
                continue;
            }

            if ($classMetadata->hasAssociation($key)) {
                if (is_string($value)) {
                    throw new BadRequestHttpException('Invalid JSON data.');
                }

                $this->parseRelationMetadata(
                    $classMetadata->getAssociationMapping($key),
                    $errors,
                    $value,
                    $key,
                    [$entity, $setterMethod]
                );
                continue;

            }
            if ($classMetadata->getFieldMapping($key)['type'] === "date") {
                $date = \DateTime::createFromFormat('Y-m-d', $value);
                if ($date === false) {
                    $errors[$key] = 'Invalid date format';
                    continue;
                }

                $entity->$setterMethod($date);
                continue;
            }

            $entity->$setterMethod($value);
        }

        if (!empty($errors)) {
            throw new JsonBadRequestException($errors);
        }

        return $entity;
    }

    private function parseRelationMetadata(
        mixed    $relationMetadata,
        array    &$errors,
        array    $value,
        string   $key,
        callable $setterMethod
    ): void
    {
        if ($relationMetadata['type'] & ClassMetadataInfo::TO_ONE) {

            if (!array_key_exists('id', $value)) {
                throw new BadRequestHttpException('Invalid JSON data.');
            }

            $relatedEntity = $this->fetchEntity(
                $value['id'],
                $relationMetadata['targetEntity']
            );
            if (!$relatedEntity) {
                $errors[$key] = "Entity not found for ID: {$value['id']}";
            }

            $setterMethod($relatedEntity);

        } elseif ($relationMetadata['type'] & ClassMetadataInfo::TO_MANY) {
            $relatedEntities = [];
            foreach ($value as $relatedData) {
                if (!array_key_exists('id', $relatedData)) {
                    throw new BadRequestHttpException('Invalid JSON data.');
                }

                $relatedEntity = $this->fetchEntity(
                    $relatedData['id'],
                    $relationMetadata['targetEntity']
                );
                if (!$relatedEntity) {
                    $errors[$key][] = "Entity not found for ID: {$relatedData['id']}";
                    continue;
                }
                $relatedEntities[] = $relatedEntity;
            }
            if (empty($errors[$key])) {
                $setterMethod(
                    new ArrayCollection($relatedEntities)
                );
            }
        }
    }

    private function fetchEntity(string $id, string $entityClass): ?object
    {
        try {
            return $this->entityManager->getRepository($entityClass)
                ->findOneBy(['id' => $id]);
        } catch (ConversionException $exception) {
            // if api gets invalid uu id form return 404
            throw new NotFoundHttpException();
        }
    }
}