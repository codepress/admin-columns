<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use DateTime;

final class DateMapper implements Formatter
{

    private string $from_format;

    private string $to_format;

    public function __construct(string $from_format, string $to_format)
    {
        $this->from_format = $from_format;
        $this->to_format = $to_format;
    }

    public function format(Value $value): Value
    {
        if (empty($value->get_value()) || ! is_scalar($value->get_value())) {
            throw new ValueNotFoundException();
        }
        
        $date = DateTime::createFromFormat($this->from_format, (string)$value->get_value());

        if ( ! $date) {
            throw new ValueNotFoundException();
        }

        return $value->with_value($date->format($this->to_format));
    }

}