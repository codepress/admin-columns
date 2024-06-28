<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Date;

use AC\Setting\Formatter;
use AC\Type\Value;

final class DateFormat implements Formatter
{

    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(ac_helper()->date->format_date($this->format, $value->get_value()));
    }

}