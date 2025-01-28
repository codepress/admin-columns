<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use DateTime;

final class DateFormat implements Formatter
{

    private string $output_format;

    private string $source_format;

    public function __construct(string $output_format, string $source_format = 'U')
    {
        $this->output_format = $output_format;
        $this->source_format = $source_format;
    }

    public function format(Value $value): Value
    {
        $date = $this->create_date((string)$value->get_value());

        if (null === $date) {
            throw new ValueNotFoundException();
        }

        return $value->with_value(
            wp_date($this->output_format, $date->getTimestamp())
        );
    }

    private function create_date(string $date_value): ?DateTime
    {
        if ( ! $date_value) {
            return null;
        }

        return DateTime::createFromFormat($this->source_format, $date_value) ?: null;
    }

}