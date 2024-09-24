<?php

declare(strict_types=1);

namespace AC\Expression;

final class ContextAwareSpecification extends CompositeSpecification
{

    private string $context;

    public function __construct(
        Specification $specification,
        string $context
    ) {
        parent::__construct($specification);

        $this->context = $context;
    }

    public function is_satisfied_by($value): bool
    {
        if ( ! $value instanceof Context || ! $value->has($this->context)) {
            return false;
        }

        return $this->specification->is_satisfied_by($value->get($this->context));
    }

}