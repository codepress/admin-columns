<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTimeZone;

class DateRangeSpecification extends DateSpecification
{

    use RangeTrait;

    public function __construct(
        string $operator,
        string $a,
        string $b,
        string $format = null,
        DateTimeZone $timezone = null
    ) {
        $this->a = $a;
        $this->b = $b;
        $this->operator = $operator;

        $this->validate_operator();

        parent::__construct($format, $timezone);
    }

    /**
     * @throws InvalidDateFormatException
     */
    protected function get_comparison_specification($fact, string $operator): Specification
    {
        return new DateComparisonSpecification((string)$fact, $operator, $this->format, $this->timezone);
    }

    public function is_satisfied_by(string $value): bool
    {
        return $this->compare($value);
    }

    protected function get_type(): string
    {
        return Types::DATE;
    }

}