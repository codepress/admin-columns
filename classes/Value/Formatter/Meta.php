<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\MetaType;
use AC\Setting\Formatter;
use AC\Type\Value;

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
        return $value->with_value(
            get_metadata(
                (string)$this->meta_type,
                (int)$value->get_id(),
                $this->meta_key,
                true
            )
        );
    }

}