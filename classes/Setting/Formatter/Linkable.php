<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Linkable implements Formatter
{

    public function format(Value $value): Value
    {
        $url = $value->get_value();

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('/[^\w.-]/', $url)) {
            return $value->with_value(ac_helper()->html->link($url, $url));
        }

        return $value;
    }

}