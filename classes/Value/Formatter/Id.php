<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class Id implements Formatter
{

    public function format(Value $value)
    {
        return $value->with_value($value->get_id());
    }

}