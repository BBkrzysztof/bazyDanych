<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\HoursInDayHandler;


/**
 * @Annotation
 * @Target("PROPERTY")
 */
class HoursInDay extends BaseValidationAnnotation
{

    public function getHandler(): string
    {
        return HoursInDayHandler::class;
    }

    public function getMessage(): string
    {
        return 'Day have only 24 hours';
    }
}