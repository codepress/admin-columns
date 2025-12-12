<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class ArrayToCollection implements Formatter
{

    private bool $convert_to_array;

    public function __construct(bool $convert_to_array = true)
    {
        $this->convert_to_array = $convert_to_array;
    }

    public function format(Value $value): ValueCollection
    {
        $array = $value->get_value();

        if (is_scalar($array) && $this->convert_to_array) {
            $array = [$array];
        }

        if ( ! is_array($array)) {
            throw new ValueNotFoundException('Invalid value type. Expected array, got: ' . gettype($array));
        }

        $collection = new ValueCollection($value->get_id());

        foreach ($array as $item) {
            $collection->add(new Value($item));
        }

        return $collection;
    }

}