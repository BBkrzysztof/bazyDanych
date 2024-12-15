<?php

namespace App\Doctrine;

use App\Interface\SoftDeleteEntityInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DeletedAtFilter extends SQLFilter
{

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {

        if (!$targetEntity->getReflectionClass()->isSubclassOf(SoftDeleteEntityInterface::class)) {
            return '';
        }

        return "$targetTableAlias.deleted_at IS NULL";
    }
}