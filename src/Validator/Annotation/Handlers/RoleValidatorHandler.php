<?php

namespace App\Validator\Annotation\Handlers;

use App\Exception\JsonBadRequestException;
use App\Validator\Annotation\BaseAnnotation\BaseValidationAnnotation;
use Security\Entity\User;
use Security\Enum\UserRolesEnum;

class RoleValidatorHandler extends BaseValidationHandler
{

    public function validate(
        string                   $name,
        mixed                    $value,
        string                   $entityClass,
        BaseValidationAnnotation $annotation
    ): bool
    {
        /** @var User $value */
        return $value->getRole() === UserRolesEnum::User->value;
    }
}