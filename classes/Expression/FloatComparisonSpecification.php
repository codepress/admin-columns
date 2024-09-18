<?php

declare(strict_types=1);

namespace AC\Expression;

class FloatComparisonSpecification extends ComparisonSpecification implements TypeSpecification
{

    public function __construct(string $operator, float $fact)
    {
        parent::__construct($operator, $fact);
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((float)$value);
    }

    public function export(): array
    {
        return array_merge([
            self::TYPE => Types::FLOAT,
        ], parent::export());
    }

}