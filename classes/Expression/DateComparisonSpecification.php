<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression;
use DateTimeZone;

class DateComparisonSpecification extends Expression\DateSpecification
{

    use ComparisonTrait {
        get_rules as get_comparison_rules;
    }

    /**
     * @throws Exception\InvalidDateFormatException
     */
    public function __construct(
        string $fact,
        string $operator,
        string $format = null,
        DateTimeZone $time_zone = null
    ) {
        parent::__construct($format, $time_zone);

        $this->fact = (int)$this->create_date_from_value($fact)->format('U');
        $this->operator = $this->map_operator($operator);

        $this->validate_operator();
    }

    private function map_operator($operator)
    {
        $map = [
            DateOperators::DATE_IS        => ComparisonOperators::EQUAL,
            DateOperators::DATE_IS_AFTER  => ComparisonOperators::GREATER_THAN,
            DateOperators::DATE_IS_BEFORE => ComparisonOperators::LESS_THAN,
        ];

        return $map[$operator] ?? $operator;
    }

    /**
     * @throws Exception\InvalidDateFormatException
     */
    public function is_satisfied_by(string $value): bool
    {
        return $this->compare(
            $this->operator,
            (int)$this->create_date_from_value($value)->format('U')
        );
    }

    protected function get_type(): string
    {
        return 'date';
    }

    public function get_rules(string $value): array
    {
        return array_merge(
            $this->get_comparison_rules($value),
            parent::get_rules($value)
        );
    }

}