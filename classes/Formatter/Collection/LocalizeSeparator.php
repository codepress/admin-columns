<?php

declare(strict_types=1);

namespace AC\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class LocalizeSeparator implements CollectionFormatter
{

    public function format(ValueCollection $collection): Value
    {
        $values = [];

        foreach ($collection as $_value) {
            $values[] = (string)$_value;
        }

        return new Value(wp_sprintf('%l', $values));
    }

}