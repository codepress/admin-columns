<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class Actions implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            '<span class="cpac_use_icons"></span>'
        );
    }

}