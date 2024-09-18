<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTime;
use DateTimeZone;

trait DateTrait
{

    protected ?string $format;

    protected ?DateTimeZone $timezone;

    /**
     * @throws InvalidDateFormatException
     */
    protected function create_date_from_value(string $value): DateTime
    {
        $date_time = DateTimeFactory::create_from_format($value, $this->format, $this->timezone);

        if ($this->format === DateFormats::MYSQL_DATE) {
            $date_time->setTime(0, 0);
        }

        return $date_time;
    }

    protected function get_date_rules(): array
    {
        return [
            DateSpecification::TIMEZONE => $this->timezone ? $this->timezone->getName() : null,
            DateSpecification::FORMAT   => $this->format,
        ];
    }

}