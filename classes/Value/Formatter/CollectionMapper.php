<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

/**
 * When the value is an array, this formatter converts them into a collection of Value objects.
 * Else it creates a single-item Value object.
 */
class CollectionMapper implements Formatter
{

    public function format(Value $value)
    {
        $data = $value->get_value();

        if ( ! $data) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (is_array($data)) {
            $collection = new ValueCollection($value->get_id());

            foreach ($data as $item) {
                $collection->add(new Value($item));
            }

            return $collection;
        }

        return $value;
    }

}