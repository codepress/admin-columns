<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\DateFormats;
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

        if ($this->format === DateFormats::DATE_MYSQL) {
            $date_time->setTime(0, 0);
        }

        return $date_time;
    }

    protected function export_date(): array
    {
        return [
            DateSpecification::TIMEZONE => $this->timezone ? $this->timezone->getName() : null,
            DateSpecification::FORMAT   => $this->format,
        ];
    }

}