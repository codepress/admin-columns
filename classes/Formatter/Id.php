<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class Id implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value($value->get_id());
    }

}