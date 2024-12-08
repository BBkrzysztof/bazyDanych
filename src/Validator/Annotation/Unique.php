<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\UniqueHandler;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Unique extends BaseValidationAnnotation
{

    public function getHandler(): string
    {
        return UniqueHandler::class;
    }

    public function getMessage(): string
    {
        return 'Field must be unique';
    }
}