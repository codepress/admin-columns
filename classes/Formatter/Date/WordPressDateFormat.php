<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Type\Value;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;

final class WordPressDateFormat extends DateObject
{

    private string $output_format;

    private ?DateTimeZone $output_timezone;

    public function __construct(string $output_format, string $source_format, ?DateTimeZone $output_timezone = null)
    {
        parent::__construct($source_format);

        $this->output_format = $output_format;
        $this->output_timezone = $output_timezone ?? wp_timezone(); // Defaults to WordPress timezone
    }

    public function format(Value $value): Value
    {
        $date = parent::format($value)->get_value();

        if ( ! $date instanceof DateTime) {
            throw new InvalidArgumentException('Invalid date object');
        }

        $date = wp_date($this->output_format, $date->getTimestamp(), $this->output_timezone);

        if (false === $date) {
            throw new InvalidArgumentException('Failed to format date');
        }

        return $value->with_value(
            $date
        );
    }

}