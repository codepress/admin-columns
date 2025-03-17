<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class ArrayToCollection implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $raw_value = $value->get_value();

        if ( ! is_array($raw_value)) {
            throw new ValueNotFoundException('No values found');
        }

        $collection = new ValueCollection($value->get_id(), []);

        foreach ($raw_value as $item) {
            $collection->add(new Value($item));
        }

        return $collection;
    }

}