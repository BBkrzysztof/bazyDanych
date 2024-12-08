<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Date extends BaseValidationAnnotation
{
    public function getMessage(): string
    {
        return '';
    }

    public function getHandler(): string
    {
        return '';
    }
}