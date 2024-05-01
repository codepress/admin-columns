<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Wrap;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ToSmall implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value(sprintf('<small>%s</small>', $value->get_value()));
    }

}