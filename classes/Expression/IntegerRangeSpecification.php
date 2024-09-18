<?php

declare(strict_types=1);

namespace AC\Expression;

class IntegerRangeSpecification extends RangeSpecification implements TypeSpecification
{

    public function __construct(string $operator, int $a, int $b)
    {
        parent::__construct($operator, $a, $b);
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((int)$value);
    }

    public function export(): array
    {
        return array_merge([
            self::TYPE => Types::INTEGER,
        ], parent::export());
    }

}