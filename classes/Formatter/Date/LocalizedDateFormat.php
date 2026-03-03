<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use DateTime;
use DateTimeZone;

final class LocalizedDateFormat extends DateObject
{

    private string $output_format;

    public function __construct(string $output_format, string $source_format)
    {
        parent::__construct($source_format);

        $this->output_format = $output_format;
    }

    public function format(Value $value): Value
    {
        $date = parent::format($value)->get_value();

        if ( ! $date instanceof DateTime) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        // Default to UTC timezone. This results in an unmodified date.
        $date = wp_date($this->output_format, $date->getTimestamp(), new DateTimeZone('UTC'));

        if (false === $date) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $date
        );
    }

}