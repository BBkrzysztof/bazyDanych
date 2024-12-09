<?php

namespace App\Validator\Annotation\Handlers;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseValidationHandler
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public abstract function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool;
}