<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use AC\Expression\Exception\OperatorNotFoundException;
use DateTimeZone;

final class DateRelativeDaysSpecification extends DateSpecification
{

    use OperatorsTrait;

    protected $fact;

    public function __construct(int $fact, string $operator, string $format = null, DateTimeZone $time_zone = null)
    {
        parent::__construct($format, $time_zone);

        $this->fact = $this->create_modified_date($this->get_modifier_string($fact, $operator));
        $this->operator = $operator;

        $this->validate_operator();
    }

    private function get_modifier_string(int $fact, $operator): string
    {
        switch ($operator) {
            case DateOperators::WITHIN_DAYS:
                return sprintf('+%d days', $fact);
            case DateOperators::GT_DAYS_AGO:
            case DateOperators::LT_DAYS_AGO:
                return sprintf('-%d days', $fact);
            default:
                return 'now';
        }
    }

    protected function get_operators(): array
    {
        return [
            DateOperators::WITHIN_DAYS,
            DateOperators::LT_DAYS_AGO,
            DateOperators::GT_DAYS_AGO,
        ];
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function is_satisfied_by(string $value): bool
    {
        // Format date to discard time
        $today = $this->get_current_date()->format(self::MYSQL_DATE);
        $date = $this->create_date_from_value($value)->format(self::MYSQL_DATE);
        $fact = $this->fact->format(self::MYSQL_DATE);

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

    public function get_rules(): array
    {
        $rules = [
            Rules::FACT     => $this->fact,
            Rules::OPERATOR => $this->operator,
        ];

        return array_merge(
            $rules,
            parent::get_rules()
        );
    }

}