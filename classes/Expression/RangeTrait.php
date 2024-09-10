<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;

trait RangeTrait
{

    use SpecificationTrait;
    use OperatorsTrait;
    use TypeTrait;

    /**
     * @var mixed
     */
    protected $a;

    /**
     * @var mixed
     */
    protected $b;

    abstract protected function get_comparison_specification($fact, string $operator): Specification;

    protected function get_operators(): array
    {
        return [
            RangeOperators::BETWEEN,
            RangeOperators::NOT_BETWEEN,
        ];
    }

    protected function compare($value): bool
    {
        switch ($this->operator) {
            case RangeOperators::BETWEEN:
            case RangeOperators::NOT_BETWEEN:
                $specification = new AndSpecification([
                    $this->get_comparison_specification($this->a, ComparisonOperators::GREATER_THAN),
                    $this->get_comparison_specification($this->b, ComparisonOperators::LESS_THAN),
                ]);

                if ($this->operator === RangeOperators::NOT_BETWEEN) {
                    $specification->not();
                }

                return $specification->is_satisfied_by($value);
        }

        throw new OperatorNotFoundException($this->operator);
    }

    public function get_rules(): array
    {
        return [
            Rules::OPERATOR => $this->operator,
            Rules::TYPE     => $this->get_type(),
            'a'             => $this->a,
            'b'             => $this->b,
        ];
    }

}