<?php

namespace App\Logger;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("METHOD")
 */
class LoggerAnnotation
{
    public string $action;
}