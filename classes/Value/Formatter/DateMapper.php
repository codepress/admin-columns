<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use DateTime;

final class DateMapper implements Formatter
{

    private string $output_format;

    private string $input_format;

    public function __construct(string $input_format, string $output_format)
    {
        $this->output_format = $output_format;
        $this->input_format = $input_format;
    }

    public function format(Value $value): Value
    {
        $date_string = $value->get_value();

        if (empty($date_string) || ! is_scalar($date_string)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = DateTime::createFromFormat($this->input_format, $date_string);

        if ( ! $date) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($date->format($this->output_format));
    }

}