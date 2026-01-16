<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Setting\CollectionFormatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class TotalSum implements CollectionFormatter
{

    public function format(ValueCollection $collection): Value
    {
        $total = 0;

        foreach ($collection as $value) {
            $total += (int)$value->get_value();
        }

        return new Value($collection->get_id(), $total);
    }

}