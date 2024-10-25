<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTime;
use DateTimeZone;

final class DateTimeFactory
{

    public static function create(?DateTimeZone $timezone = null): DateTime
    {
        return new DateTime('now', self::resolve_timezone($timezone));
    }

    /**
     * @throws InvalidDateFormatException
     */
    public static function create_from_format(
        $date_time,
        ?string $format = null,
        ?DateTimeZone $timezone = null
    ): DateTime {
        $parsed = DateTime::createFromFormat(
            $format ?: DateFormats::MYSQL_DATE,
            $date_time,
            self::resolve_timezone($timezone)
        );

        if ( ! $parsed) {
            throw new InvalidDateFormatException($date_time, (string)$format);
        }

        return $parsed;
    }

    private static function resolve_timezone(?DateTimeZone $timezone): DateTimeZone
    {
        return $timezone ?? wp_timezone();
    }

}