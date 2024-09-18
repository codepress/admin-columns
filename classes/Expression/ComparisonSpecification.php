<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;

class ComparisonSpecification extends Specification implements FactSpecification
{

    /**
     * @var mixed
     */
    protected $fact;

    /**
     * @param $fact mixed
     */
    public function __construct(
        string $operator,
        $fact
    ) {
        parent::__construct($operator);

        $this->fact = $fact;
    }

    public function is_satisfied_by($value): bool
    {
        switch ($this->operator) {
            case ComparisonOperators::EQUAL:
                return $value === $this->fact;
            case ComparisonOperators::NOT_EQUAL:
                return $value !== $this->fact;
            case ComparisonOperators::GREATER_THAN:
                return $value > $this->fact;
            case ComparisonOperators::GREATER_THAN_EQUAL:
                return $value >= $this->fact;
            case ComparisonOperators::LESS_THAN:
                return $value < $this->fact;
            case ComparisonOperators::LESS_THAN_EQUAL:
                return $value <= $this->fact;
        }

        throw new OperatorNotFoundException($this->operator);
    }

    public function export(): array
    {
        return array_merge([
            self::FACT => $this->fact,
        ], parent::export());
    }

}