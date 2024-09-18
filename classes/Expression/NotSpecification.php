<?php

declare(strict_types=1);

namespace AC\Expression;

final class NotSpecification extends Specification
{

    public const RULE = 'rule';

    private Specification $specification;

    public function __construct(Specification $specification)
    {
        parent::__construct(LogicalOperators::LOGICAL_NOT);

        $this->specification = $specification;
    }

    public function is_satisfied_by($value): bool
    {
        return ! $this->specification->is_satisfied_by($value);
    }

    public function export(): array
    {
        return array_merge([
            self::RULE => $this->specification->export(),
        ], parent::export());
    }

}