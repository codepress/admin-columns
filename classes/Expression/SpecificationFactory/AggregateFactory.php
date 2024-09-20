<?php

declare(strict_types=1);

namespace AC\Expression\SpecificationFactory;

use AC\Expression\AggregateSpecification;
use AC\Expression\AndSpecification;
use AC\Expression\CollectionSpecification;
use AC\Expression\ContainsSpecification;
use AC\Expression\DateComparisonSpecification;
use AC\Expression\DateRangeSpecification;
use AC\Expression\DateRelativeDaysSpecification;
use AC\Expression\DateRelativeDeductedSpecification;
use AC\Expression\DateSpecification;
use AC\Expression\EndsWithSpecification;
use AC\Expression\Exception\InvalidDateFormatException;
use AC\Expression\Exception\SpecificationNotFoundException;
use AC\Expression\FactSpecification;
use AC\Expression\FloatComparisonSpecification;
use AC\Expression\FloatRangeSpecification;
use AC\Expression\IntegerComparisonSpecification;
use AC\Expression\IntegerRangeSpecification;
use AC\Expression\OperatorExpression;
use AC\Expression\OrSpecification;
use AC\Expression\RangeSpecification;
use AC\Expression\Specification;
use AC\Expression\SpecificationFactory;
use AC\Expression\StartsWithSpecification;
use InvalidArgumentException;

final class AggregateFactory implements SpecificationFactory
{

    /**
     * @throws InvalidDateFormatException
     */
    public function create(array $rule): Specification
    {
        if ( $rule[Specification::SPECIFICATION] ?? null ) {
            throw new InvalidArgumentException('Missing specification.');
        }

        $specification = $rule[Specification::SPECIFICATION];
        $operator = $rule[OperatorExpression::OPERATOR] ?? null;
        $fact = $rule[FactSpecification::FACT] ?? null;
        $format = $rule[DateSpecification::FORMAT] ?? null;
        $timezone = $rule[DateSpecification::TIMEZONE] ?? null;
        $a = $rule[RangeSpecification::A] ?? null;
        $b = $rule[RangeSpecification::B] ?? null;

        switch ($specification) {
            case 'starts_with':
                return new StartsWithSpecification($fact);
            case 'ends_with':
                return new EndsWithSpecification($fact);
            case 'contains':
                return new ContainsSpecification($fact);
            case 'not_contains':
                return (new ContainsSpecification($fact))->not();
            case 'float_comparison':
                return new FloatComparisonSpecification($operator, (float)$fact);
            case 'integer_comparison':
                return new IntegerComparisonSpecification($operator, (int)$fact);
            case 'date_comparison':
                return new DateComparisonSpecification($operator, (string)$fact, $format, $timezone);
            case 'date_relative_days':
                return new DateRelativeDaysSpecification($operator, (int)$fact, $format, $timezone);
            case 'date_relative_deducted':
                return new DateRelativeDeductedSpecification($operator, $format, $timezone);
            case 'range_float':
                return new FloatRangeSpecification($operator, (float)$a, (float)$b);
            case 'range_integer':
                return new IntegerRangeSpecification($operator, (int)$a, (int)$b);
            case 'range_date_time':
                return new DateRangeSpecification($operator, (string)$a, (string)$b, $format, $timezone);
            case 'collection':
                // TODO David test for traversing traversable?
                return new CollectionSpecification($operator, $fact);
            case 'and':
            case 'or':
                $rules = [];

                foreach ($rule[AggregateSpecification::RULES] as $aggregate_rule) {
                    $rules[] = $this->create($aggregate_rule);
                }

                return $specification === 'and'
                    ? new AndSpecification($rules)
                    : new OrSpecification($rules);
        }

        throw new SpecificationNotFoundException($specification);
    }

}