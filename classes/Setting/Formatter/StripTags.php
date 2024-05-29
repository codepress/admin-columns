<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class StripTags implements Formatter
{

    public function format(Value $value): Value
    {
        $string = (string)$value->get_value();

        return $value->with_value(
            wp_strip_all_tags($string)
        );
    }

}