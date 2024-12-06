<?php

namespace Security\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("METHOD")
 */
class RequiredFields
{
    public array $fields = [];
}