<?php

declare(strict_types=1);

namespace AC\Expression;

final class NotSpecification extends CompositeSpecification
{

    public function is_satisfied_by($value): bool
    {
        return ! $this->specification->is_satisfied_by($value);
    }

}