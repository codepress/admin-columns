<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class LocalizeSeparator implements CollectionFormatter
{

    public function format(ValueCollection $value): Value
    {
        $values = [];

        foreach ($value as $_value) {
            $values[] = (string)$_value;
        }

        return new Value(wp_sprintf('%l', $values));
    }

}