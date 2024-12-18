<?php

namespace App\Paginator\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Pagination
{
    public array $likeFilters = [];
    public array $eqFilters = [];
}