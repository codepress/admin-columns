<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\CollectionFormatter;
use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Count implements CollectionFormatter
{

    public function format(ValueCollection $collection): Value
    {
        $count = count($collection);

        if ($count === 0) {
            throw new ValueNotFoundException('Collection can not be empty. id: ' . $collection->get_id());
        }

        return new Value($count);
    }

}