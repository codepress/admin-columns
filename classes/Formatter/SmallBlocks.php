<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class SmallBlocks implements Formatter
{

    public function format(Value $value): Value
    {
        $values = (array)$value->get_value();

        return $value->with_value(
            ac_helper()->html->small_block($values)
        );
    }

}