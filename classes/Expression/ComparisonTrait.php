<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;

trait ComparisonTrait
{

    use OperatorsTrait;
    use TypeTrait;

    /**
     * @var mixed
     */
    protected $fact;

    protected function get_operators(): array
    {
        return [
            ComparisonOperators::EQUAL,
            ComparisonOperators::NOT_EQUAL,
            ComparisonOperators::LESS_THAN,
            ComparisonOperators::LESS_THAN_EQUAL,
            ComparisonOperators::GREATER_THAN,
            ComparisonOperators::GREATER_THAN_EQUAL,
        ];
    }

    protected function compare(string $operator, $value): bool
    {
        switch ($operator) {
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

        throw new OperatorNotFoundException($operator);
    }

    public function get_rules(): array
    {
        return [
            Rules::OPERATOR => $this->operator,
            Rules::TYPE     => $this->get_type(),
            Rules::FACT     => $this->fact,
        ];
    }

}