<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use DateTime;

final class DateMapper implements Formatter
{

    private string $output_format;

    /**
     * @param string Default will try to automatically detect the format.
     */
    private ?string $source_format;

    public function __construct(string $output_format, ?string $source_format = null)
    {
        $this->output_format = $output_format;
        $this->source_format = $source_format;
    }

    public function format(Value $value): Value
    {
        $date_string = (string)$value;

        if ( ! $date_string) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = $this->create_date($date_string);

        if ( ! $date) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $date->format($this->output_format)
        );
    }

    private function create_date(string $date_string): ?DateTime
    {
        if ($this->source_format) {
            return DateTime::createFromFormat($this->source_format, $date_string) ?: null;
        }

        // try to automatically detect the format and create a DateTime object
        $timestamp = ac_helper()->date->strtotime($date_string);

        if ( ! $timestamp) {
            throw new ValueNotFoundException('Invalid timestamp.');
        }

        return DateTime::createFromFormat('U', $timestamp) ?: null;
    }

}