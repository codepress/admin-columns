<?php

declare(strict_types=1);

namespace AC\Expression;

class IntegerComparisonSpecification implements Specification
{

    use SpecificationTrait;
    use ComparisonTrait;

    public function __construct(int $fact, string $operator)
    {
        $this->fact = $fact;
        $this->operator = $operator;

        $this->validate_operator();
    }

    public function is_satisfied_by(string $value): bool
    {
        return $this->compare($this->operator, (int)$value);
    }

    protected function get_type(): string
    {
        return 'integer';
    }

}