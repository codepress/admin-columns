<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use DateTime;

final class TimeDifference implements Formatter
{

    private string $source_format;

    public function __construct(string $source_format = 'U')
    {
        $this->source_format = $source_format;
    }

    private function create_date(string $date_value): ?DateTime
    {
        if ( ! $date_value) {
            return null;
        }

        return DateTime::createFromFormat($this->source_format, $date_value) ?: null;
    }

    public function format(Value $value): Value
    {
        $date = $this->create_date((string)$value->get_value());

        if (null === $date) {
            throw new ValueNotFoundException();
        }

        $timestamp = $date->getTimestamp();
        $current_time = current_time('timestamp');

        $tpl = __('%s ago');

        if ($timestamp > $current_time) {
            $tpl = __('in %s', 'codepress-admin-columns');
        }

        return $value->with_value(sprintf($tpl, human_time_diff($timestamp, $current_time)));
    }

}