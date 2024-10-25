<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use AC\Expression\Exception\OperatorNotFoundException;
use DateTimeZone;

final class DateRelativeDaysSpecification extends OperatorExpression implements FactSpecification
{

    use DateTrait;
    use FactTrait;

    public function __construct(string $operator, int $fact, string $format = null, DateTimeZone $timezone = null)
    {
        parent::__construct($operator);

        $this->fact = DateTimeFactory::create($timezone)->modify($this->get_modifier_string($fact, $operator));
        $this->format = $format;
        $this->timezone = $timezone;
    }

    private function get_modifier_string(int $fact, $operator): string
    {
        switch ($operator) {
            case DateOperators::WITHIN_DAYS:
                return sprintf('+%d days', $fact);
            case DateOperators::GT_DAYS_AGO:
            case DateOperators::LT_DAYS_AGO:
                return sprintf('-%d days', $fact);
        }

        throw new OperatorNotFoundException($operator);
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function is_satisfied_by($value): bool
    {
        // Format that discards time
        $format = DateFormats::MYSQL_DATE;

        $today = DateTimeFactory::create($this->timezone)->format($format);
        $date = DateTimeFactory::create_from_format($value, $this->format, $this->timezone)->format($format);
        $fact = $this->fact->format($format);

        switch ($this->operator) {
            case DateOperators::WITHIN_DAYS:
                return $date <= $fact && $date >= $today;
            case DateOperators::LT_DAYS_AGO:
                return $date <= $today && $date >= $fact;
            case DateOperators::GT_DAYS_AGO:
                return $date <= $fact;
        }

        throw new OperatorNotFoundException($this->operator);
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_date(),
            $this->export_fact()
        );
    }

}