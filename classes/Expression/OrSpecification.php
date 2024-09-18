<?php

declare(strict_types=1);

namespace AC\Expression;

final class OrSpecification extends AggregateSpecification
{

    public function __construct(array $specifications)
    {
        parent::__construct(
            LogicalOperators::LOGICAL_OR,
            $specifications
        );
    }

    public function is_satisfied_by($value): bool
    {
        foreach ($this->specifications as $specification) {
            if ($specification->is_satisfied_by($value)) {
                return true;
            }
        }

        return false;
    }

}