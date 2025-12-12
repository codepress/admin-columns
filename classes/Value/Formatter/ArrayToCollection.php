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
        $array = $value->get_value();

        if ( ! is_array($array) || ! $array) {
            throw new ValueNotFoundException('No array found. ID . ' . $value->get_id());
        }

        $collection = new ValueCollection($value->get_id());

        foreach ($array as $item) {
            $collection->add(new Value($item));
        }

        return $collection;
    }

}