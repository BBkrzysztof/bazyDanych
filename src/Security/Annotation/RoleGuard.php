<?php

namespace Security\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class RoleGuard
{
    public string $role;
}