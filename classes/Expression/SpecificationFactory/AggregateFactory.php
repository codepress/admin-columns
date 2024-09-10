<?php

declare(strict_types=1);

namespace AC\Expression\SpecificationFactory;

use AC\Expression\AndSpecification;
use AC\Expression\ComparisonOperators;
use AC\Expression\ContainsSpecification;
use AC\Expression\DateComparisonSpecification;
use AC\Expression\DateOperators;
use AC\Expression\DateRangeSpecification;
use AC\Expression\DateRelativeDaysSpecification;
use AC\Expression\DateRelativeDeductedSpecification;
use AC\Expression\EndsWithSpecification;
use AC\Expression\Exception\OperatorNotFoundException;
use AC\Expression\FloatComparisonSpecification;
use AC\Expression\FloatRangeSpecification;
use AC\Expression\IntegerComparisonSpecification;
use AC\Expression\IntegerRangeSpecification;
use AC\Expression\LogicalOperators;
use AC\Expression\OrSpecification;
use AC\Expression\RangeOperators;
use AC\Expression\Rules;
use AC\Expression\Specification;
use AC\Expression\SpecificationFactory;
use AC\Expression\StartsWithSpecification;
use AC\Expression\StringComparisonSpecification;
use AC\Expression\StringOperators;
use AC\Expression\StringRangeSpecification;
use AC\Expression\Types;
use InvalidArgumentException;

final class AggregateFactory implements SpecificationFactory
{

    public function create(array $rule): Specification
    {
        if ( ! $rule[Rules::OPERATOR]) {
            throw new InvalidArgumentException('Missing operator.');
        }

        $operator = (string)$rule[Rules::OPERATOR];
        $fact = $rule[Rules::FACT] ?? null;
        $type = $rule[Rules::TYPE] ?? null;

        switch ($operator) {
            case StringOperators::STARTS_WITH:
                return new StartsWithSpecification($this->sanitize_fact($fact));
            case StringOperators::ENDS_WITH:
                return new EndsWithSpecification($this->sanitize_fact($fact));
            case StringOperators::CONTAINS:
            case StringOperators::NOT_CONTAINS:
                $specification = new ContainsSpecification($this->sanitize_fact($fact));

                if ($operator === StringOperators::NOT_CONTAINS) {
                    $specification = $specification->not();
                }

                return $specification;
            case DateOperators::TODAY:
            case DateOperators::FUTURE:
            case DateOperators::PAST:
                return new DateRelativeDeductedSpecification($operator);
            case DateOperators::WITHIN_DAYS:
            case DateOperators::GT_DAYS_AGO:
            case DateOperators::LT_DAYS_AGO:
                return new DateRelativeDaysSpecification((int)$fact, $operator);
            case ComparisonOperators::EQUAL:
            case ComparisonOperators::NOT_EQUAL:
            case ComparisonOperators::LESS_THAN:
            case ComparisonOperators::LESS_THAN_EQUAL:
            case ComparisonOperators::GREATER_THAN:
            case ComparisonOperators::GREATER_THAN_EQUAL:
                switch ($type) {
                    case Types::INTEGER:
                        return new IntegerComparisonSpecification((int)$fact, $operator);
                    case Types::FLOAT:
                        return new FloatComparisonSpecification((float)$fact, $operator);
                    case Types::DATE:
                        // TODO David find out why we support floats as well
                        if (false !== filter_var($fact, FILTER_SANITIZE_NUMBER_INT)) {
                            return new IntegerComparisonSpecification((int)$fact, $operator);
                        }

                        if (false !== filter_var($fact, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND)) {
                            return new FloatComparisonSpecification((int)$fact, $operator);
                        }

                        return new DateComparisonSpecification($fact, $operator);
                }

                return new StringComparisonSpecification($this->sanitize_fact($fact), $operator);
            case RangeOperators::BETWEEN:
            case RangeOperators::NOT_BETWEEN:
                switch ($type) {
                    case Types::INTEGER:
                        $specification = new IntegerRangeSpecification(
                            $operator,
                            (int)$rule['a'],
                            (int)$rule['b']
                        );

                        break;
                    case Types::FLOAT:
                        $specification = new FloatRangeSpecification(
                            $operator,
                            (float)$rule['a'],
                            (float)$rule['b']
                        );

                        break;
                    case Types::DATE:
                        $specification = new DateRangeSpecification(
                            $operator,
                            $rule['a'],
                            $rule['b'],
                            $rule['format'] ?? null,
                            $rule['timezone'] ?? null
                        );

                        break;
                    default:
                        $specification = new StringRangeSpecification(
                            $operator,
                            $rule['a'],
                            $rule['b']
                        );
                }

                if ($operator === RangeOperators::NOT_BETWEEN) {
                    $specification = $specification->not();
                }

                return $specification;
            case LogicalOperators::LOGICAL_OR:
                return new AndSpecification($rule['rules']);
            case LogicalOperators::LOGICAL_AND:
                return new OrSpecification($rule['rules']);
        }

        throw new OperatorNotFoundException($operator);
    }

    // TODO David this feels weird
    private function sanitize_fact($fact): string
    {
        return strtolower((string)$fact);
    }

}