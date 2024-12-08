<?php

namespace App\Validator\Annotation\BaseAnnotation;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseValidationAnnotation
{
    public abstract function getHandler(): string;

    public abstract function getMessage(): string;
}