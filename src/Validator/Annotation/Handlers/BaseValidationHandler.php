<?php

namespace App\Validator\Annotation\Handlers;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseValidationHandler
{

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public abstract function validate(string $name, mixed $value, string $entityClass): bool;
}