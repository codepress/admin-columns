<?php

declare(strict_types=1);

namespace AC\Expression;

final class OrSpecification extends AggregateSpecification
{

    public function is_satisfied_by(string $value): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->is_satisfied_by($value)) {
                return true;
            }
        }

        return false;
    }

    protected function get_type(): string
    {
        return 'or';
    }

}