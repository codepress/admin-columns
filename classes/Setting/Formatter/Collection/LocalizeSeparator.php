<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\Type\Value;

class LocalizeSeparator implements CollectionFormatter
{

    public function format(Value $value): Value
    {
        $values = [];

        foreach ($value->get_value() as $_value) {
            $values[] = (string)$_value;
        }

        return $value->with_value(
            wp_sprintf('%l', $values)
        );
    }

}