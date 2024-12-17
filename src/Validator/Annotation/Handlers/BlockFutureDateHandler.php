<?php

namespace App\Validator\Annotation\Handlers;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use DateTime;

class BlockFutureDateHandler extends BaseValidationHandler
{
    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {
        /** @var DateTime $value */
        return $value <= new DateTime();
    }
}