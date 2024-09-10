<?php

declare(strict_types=1);

namespace AC\Expression;

class FloatRangeSpecification implements Specification
{

    use RangeTrait;

    public function __construct(string $operator, float $a, float $b)
    {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $operator;

        $this->validate_operator();
    }

    protected function get_comparison_specification($fact, string $operator): Specification
    {
        return new FloatComparisonSpecification((float)$fact, $operator);
    }

    public function is_satisfied_by($value): bool
    {
        return $this->compare((float)$value);
    }

    protected function get_type(): string
    {
        return Types::FLOAT;
    }

}