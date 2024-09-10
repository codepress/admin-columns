<?php

declare(strict_types=1);

namespace AC\Expression;

class IntegerRangeSpecification implements Specification
{

    use RangeTrait;

    public function __construct(string $operator, int $a, int $b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $operator;

        $this->validate_operator();
    }

    protected function get_comparison_specification(int $fact, string $operator): Specification
    {
        return new IntegerComparisonSpecification($fact, $operator);
    }

    public function is_satisfied_by($value): bool
    {
        return $this->compare((int)$value);
    }

    protected function get_type(): string
    {
        return Types::INTEGER;
    }

}