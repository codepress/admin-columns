<?php

declare(strict_types=1);

namespace AC\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use DateTime;

class DateObject implements Formatter
{
    private string $source_format;

    public function __construct(string $source_format)
    {
        $this->source_format = $source_format;
    }

    public function format(Value $value): Value
    {
        $date_string = (string)$value;

        if (! $date_string) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $date = DateTime::createFromFormat($this->get_parse_format(), $date_string) ?: null;

        if (null === $date || $this->is_invalid_parsed_date()) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $date
        );
    }

    // Without "|" a format that has no time is parsed with the current time filled in
    private function get_parse_format(): string
    {
        return preg_match('/(?<!\\\\)[aABgGhHisuvU]/', $this->source_format)
            ? $this->source_format
            : $this->source_format . '|';
    }

    /**
     * Checks whether a DateTime parsing operation failed due to an invalid date
     * (for example: "0000-00-00 00:00:00").
     * This function must be called immediately after DateTime::createFromFormat(),
     * as DateTime::getLastErrors() is global and resets on the next parse.
     * @return bool True if the date was parsed but marked invalid, false otherwise.
     */
    public function is_invalid_parsed_date(): bool
    {
        $errors = DateTime::getLastErrors();

        if ($errors === false) {
            return false;
        }

        $errors = (array)$errors;

        return ($errors['warning_count'] ?? 0) > 0
               && isset($errors['warnings'])
               && in_array('The parsed date was invalid', $errors['warnings'], true);
    }

}
