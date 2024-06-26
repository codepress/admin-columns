<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\MetaType;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class MetaCollection implements Formatter
{

    private $meta_type;

    private $meta_key;

    public function __construct(MetaType $meta_type, string $meta_key)
    {
        $this->meta_type = $meta_type;
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): ValueCollection
    {
        $values = get_metadata($this->meta_type, (int)$value->get_id(), $this->meta_key);
        $values = array_filter($values);

        $value_collection = new ValueCollection($value->get_id());

        foreach ($values as $single_value) {
            $value_collection->add(new Value($value->get_id(), $single_value));
        }

        return $value_collection;
    }

}