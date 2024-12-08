<?php

namespace App\Validator;

use Doctrine\Common\Annotations\Reader;


class Validator
{

    private Reader $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    public function validate(mixed $entity, array $groups): void
    {

    }
}