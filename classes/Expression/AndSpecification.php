<?php

declare(strict_types=1);

namespace AC\Expression;

final class AndSpecification extends AggregateSpecification
{

    public function is_satisfied_by($value): bool
    {
        foreach ($this->specifications as $specification) {
            if ( ! $specification->is_satisfied_by($value)) {
                return false;
            }
        }

        return true;
    }

}