<?php

namespace App\Validator\Annotation\Handlers;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;

class HoursInDayHandler extends BaseValidationHandler
{

    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {
        return $value <= 24;
    }
}