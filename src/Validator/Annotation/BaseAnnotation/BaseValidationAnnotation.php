<?php

namespace App\Validator\Annotation\BaseAnnotation;

abstract class BaseValidationAnnotation
{
    public abstract function validate($name, $field): bool;
}