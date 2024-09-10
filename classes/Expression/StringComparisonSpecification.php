<?php

declare(strict_types=1);

namespace AC\Expression;

class StringComparisonSpecification implements Specification
{

    use SpecificationTrait;
    use ComparisonTrait;

    public function __construct(string $fact, string $operator)
    {
        $this->fact = $fact;
        $this->operator = $operator;

        $this->validate_operator();
    }

    public static function equal(string $fact): self
    {
        return new self($fact, ComparisonOperators::EQUAL);
    }

    public function is_satisfied_by(string $value): bool
    {
        return $this->compare($this->operator, $value);
    }

    protected function get_type(): string
    {
        return Types::STRING;
    }

}