<?php

namespace App\Validator\Annotation;

use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use App\Validator\Annotation\Handlers\OneOfHandler;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class OneOf extends BaseValidationAnnotation
{
    public string $enumPath;

    public function getHandler(): string
    {
        return OneOfHandler::class;
    }

    public function getMessage(): string
    {
        $cases = array_map(fn($case) => $case->value, $this->enumPath::cases());
        $cases = implode(', ', $cases);
        return "Must be one of: {$cases}";
    }
}
