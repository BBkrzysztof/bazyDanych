<?php

namespace App\Validator\Annotation;


use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\EmailHandler;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Email extends BaseValidationAnnotation
{

    public function getHandler(): string
    {
        return EmailHandler::class;
    }

    public function getMessage(): string
    {
        return 'email is not valid';
    }
}