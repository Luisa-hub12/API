<?php

namespace App\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class SoftDeleteFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if (false === $targetEntity->hasField('deletedAt')) {
            return '';
        }
        return sprintf('%s.deleted_at IS NULL', $targetTableAlias);
    }
}
