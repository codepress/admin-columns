<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Type\Value;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;

/**
 * WordPress Date Format
 * This will always apply the wp_timezone() to the date and uses wp_date() to format the date.
 */
final class WordPressDateFormat extends DateObject
{

    private string $output_format;

    private ?DateTimeZone $timezone;

    public function __construct(string $output_format, string $source_format, ?DateTimeZone $timezone = null)
    {
        parent::__construct($source_format);

        $this->output_format = $output_format;
        $this->timezone = $timezone;
    }

    public function format(Value $value): Value
    {
        $date = parent::format($value)->get_value();

        if ( ! $date instanceof DateTime) {
            throw new InvalidArgumentException('Invalid date object');
        }

        $date = wp_date($this->output_format, $date->getTimestamp(), $this->timezone);

        if (false === $date) {
            throw new InvalidArgumentException('Failed to format date');
        }

        return $value->with_value(
            $date
        );
    }

}