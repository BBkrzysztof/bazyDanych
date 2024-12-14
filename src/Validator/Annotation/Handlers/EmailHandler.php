<?php

namespace App\Validator\Annotation\Handlers;


use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;

class EmailHandler extends BaseValidationHandler
{

    private const EMAIL_REGEX = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {
        return preg_match(self::EMAIL_REGEX, $value);
    }
}