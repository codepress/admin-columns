<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\MetaType;
use AC\Setting\Formatter;
use AC\Type\Value;

class MetaCount implements Formatter
{

    private MetaType $meta_type;

    private string $meta_key;

    public function __construct(MetaType $meta_type, string $meta_key)
    {
        $this->meta_type = $meta_type;
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): Value
    {
        $values = get_metadata($this->meta_type, (int)$value->get_id(), $this->meta_key);

        return $value->with_value(count($values));
    }

}