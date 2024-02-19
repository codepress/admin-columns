<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\MetaType;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Meta implements Formatter
{

    private $meta_type;

    private $meta_key;

    public function __construct(MetaType $meta_type, string $meta_key)
    {
        $this->meta_type = $meta_type;
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): Value
    {
        return get_metadata($this->meta_type, (int)$value->get_id(), $this->meta_key);
    }

}