<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\MetaType;
use AC\Type\Value;
use AC\Type\ValueCollection;

class MetaCollection implements Formatter
{

    private MetaType $meta_type;

    private string $meta_key;

    public function __construct(MetaType $meta_type, string $meta_key)
    {
        $this->meta_type = $meta_type;
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): ValueCollection
    {
        $id = $value->get_id();

        if ( ! $this->meta_key) {
            throw ValueNotFoundException::from_id($id);
        }

        $values = get_metadata((string)$this->meta_type, (int)$id, $this->meta_key);

        if ( ! $values || ! is_array($values)) {
            throw ValueNotFoundException::from_id($id);
        }

        $collection = new ValueCollection($id);

        foreach (array_filter($values) as $item) {
            $collection->add(new Value($item));
        }

        return $collection;
    }

}