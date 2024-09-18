<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;

class RangeSpecification extends Specification
{

    public const A = 'a';
    public const B = 'b';

    /**
     * @var mixed
     */
    protected $a;

    /**
     * @var mixed
     */
    protected $b;

    public function __construct(string $operator, $a, $b)
    {
        parent::__construct($operator);

        $this->a = $a;
        $this->b = $b;
    }


    public function is_satisfied_by($value): bool
    {
        switch ($this->operator) {
            case RangeOperators::BETWEEN_EXCLUSIVE:
            case RangeOperators::NOT_BETWEEN_EXCLUSIVE:
            case RangeOperators::BETWEEN:
            case RangeOperators::NOT_BETWEEN:
                $operator_a = ComparisonOperators::GREATER_THAN_EQUAL;
                $operator_b = ComparisonOperators::LESS_THAN_EQUAL;

                $exclusive = [
                    RangeOperators::BETWEEN_EXCLUSIVE,
                    RangeOperators::NOT_BETWEEN_EXCLUSIVE,
                ];

                if (in_array($this->operator, $exclusive, true)) {
                    $operator_a = ComparisonOperators::GREATER_THAN;
                    $operator_b = ComparisonOperators::LESS_THAN;
                }

                $specification = new AndSpecification([
                    new ComparisonSpecification($this->a, $operator_a),
                    new ComparisonSpecification($this->b, $operator_b),
                ]);

                $not = [
                    RangeOperators::NOT_BETWEEN,
                    RangeOperators::NOT_BETWEEN_EXCLUSIVE,
                ];

                if (in_array($this->operator, $not, true)) {
                    $specification = $specification->not();
                }

                return $specification->is_satisfied_by($value);
        }

        throw new OperatorNotFoundException($this->operator);
    }

    public function export(): array
    {
        return array_merge([
            self::A             => $this->a,
            self::B             => $this->b,
        ], parent::export());
    }

}