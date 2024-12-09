<?php

namespace Security\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class RoleGuard
{
    public array $roles = [];
}