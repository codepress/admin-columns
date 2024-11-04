<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class StripNewLine implements Formatter
{

    public function format(Value $value)
    {
        $string = str_replace(
            [
                '<br/>',
                '<br>',
                '<br />',
            ],
            ' ',
            (string)$value
        );

        return $value->with_value(strip_tags($string));
    }

}