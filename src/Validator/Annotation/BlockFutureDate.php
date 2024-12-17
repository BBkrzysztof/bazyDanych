<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\BlockFutureDateHandler;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class BlockFutureDate extends BaseValidationAnnotation
{

    public function getHandler(): string
    {
        return BlockFutureDateHandler::class;
    }

    public function getMessage(): string
    {
        return 'Date cant be in future';
    }
}