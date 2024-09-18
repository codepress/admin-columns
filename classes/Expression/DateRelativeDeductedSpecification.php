<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use AC\Expression\Exception\OperatorNotFoundException;
use DateTimeZone;

final class DateRelativeDeductedSpecification extends Specification
{

    use DateTrait;


    public function __construct(string $operator, ?string $format = null, ?DateTimeZone $timezone = null)
    {
        parent::__construct($operator);

        $this->format = $format;
        $this->timezone = $timezone;
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function is_satisfied_by($value): bool
    {
        // Format that discards time
        $format = DateFormats::MYSQL_DATE;

        $date = $this->create_date_from_value($value)->format($format);
        $today = DateTimeFactory::create( $this->timezone )->format($format);

        switch ($this->operator) {
            case DateOperators::TODAY:
                return $today === $date;
            case DateOperators::PAST:
                return $date < $today;
            case DateOperators::FUTURE:
                return $date > $today;
        }

        throw new OperatorNotFoundException($this->operator);
    }

}