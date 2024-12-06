<?php

namespace App\Doctrine;

use Laminas\Code\Generator\AbstractGenerator;
use Ramsey\Uuid\Uuid;

class UuidGenerator extends AbstractGenerator
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}