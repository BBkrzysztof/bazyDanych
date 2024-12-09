<?php

namespace App\Validator;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Persistence\Proxy;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Validator
{
    private Reader $annotationReader;
    private ContainerInterface $container;

    public function __construct(Reader $annotationReader, ContainerInterface $container)
    {
        $this->annotationReader = $annotationReader;
        $this->container = $container;
    }

    /**
     * @throws \ReflectionException
     */
    public function validate(mixed $entity, array $groups = []): array
    {
        $errors = [];

        if ($entity instanceof Proxy) {
            $entity->__load();
        }

        $reflection = new ReflectionClass($entity instanceof Proxy ? current(class_parents($entity)) : $entity);

        foreach ($reflection->getProperties() as $property) {
            $this->validateProperty($entity, $property, $errors, $groups);
        }

        return $errors;
    }

    private function validateProperty(
        object             $entity,
        ReflectionProperty $property,
        array              &$errors,
        array              $groups,

    ): void
    {
        $annotations = $this->annotationReader->getPropertyAnnotations($property);
        foreach ($annotations as $annotation) {
            if ($annotation instanceof BaseValidationAnnotation) {

                if (!empty($groups) && empty(array_intersect($groups, $annotation->groups))) {
                    continue;
                }

                $handler = $this->getHandler($annotation->getHandler());
                if (!$handler->validate(
                    $property->name,
                    $property->getValue($entity),
                    $entity::class,
                    $annotation
                )) {
                    $errors[$property->name] = $annotation->getMessage();
                }
            }
        }

    }

    private function getHandler(string $handler): ?object
    {
        return $this->container->get($handler);
    }
}