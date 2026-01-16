<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Type\Value;
use DateTime;
use InvalidArgumentException;

final class DateFormat extends DateObject
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
            throw new InvalidArgumentException('Invalid date object');
        }

        return $value->with_value(
            $date->format($this->output_format)
        );
    }

}