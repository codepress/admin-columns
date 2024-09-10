<?php

declare(strict_types=1);

namespace AC\Expression;

class StringRangeSpecification implements Specification
{

    use RangeTrait;

    public function __construct(string $operator, string $a, string $b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $operator;

        $this->validate_operator();
    }

    protected function get_comparison_specification(string $fact, string $operator): Specification
    {
        return new StringComparisonSpecification($fact, $operator);
    }

    public function is_satisfied_by(string $value): bool
    {
        return $this->compare($value);
    }

    protected function get_type(): string
    {
        return Types::STRING;
    }

}