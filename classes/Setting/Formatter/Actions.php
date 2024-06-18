<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Actions implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            '<span class="cpac_use_icons"></span>'
        );
    }

}