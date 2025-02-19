<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\RoleValidatorHandler;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class RoleValidator extends BaseValidationAnnotation
{

    public function getHandler(): string
    {
        return RoleValidatorHandler::class;
    }

    public function getMessage(): string
    {
        return 'User must have role Employee or Admin';
    }
}