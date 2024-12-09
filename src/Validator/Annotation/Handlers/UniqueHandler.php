<?php

namespace App\Validator\Annotation\Handlers;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;

class UniqueHandler extends BaseValidationHandler
{

    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {

        $entity = $this->entityManager->getRepository($entityClass)
            ->findOneBy([$name => $value]);

        return is_null($entity);
    }
}