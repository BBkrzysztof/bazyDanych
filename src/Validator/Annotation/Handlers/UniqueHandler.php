<?php

namespace App\Validator\Annotation\Handlers;

class UniqueHandler extends BaseValidationHandler
{

    public function validate(string $name, mixed $value, string $entityClass): bool
    {

        $entity = $this->entityManager->getRepository($entityClass)
            ->findOneBy([$name => $value]);

        return !is_null($entity);
    }
}