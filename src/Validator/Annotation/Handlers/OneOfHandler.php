<?php

namespace App\Validator\Annotation\Handlers;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\OneOf;
use InvalidArgumentException;

class OneOfHandler extends BaseValidationHandler
{

    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {

        if (!enum_exists($annotation->enumPath)) {
            throw new InvalidArgumentException("Enum {$annotation->enumPath} does not exist.");
        }

        /** @var OneOf $annotation */
        return $annotation->enumPath::tryFrom($value) !== null;
    }
}