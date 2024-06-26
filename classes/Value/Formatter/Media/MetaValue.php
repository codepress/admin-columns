<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class MetaValue implements Formatter
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_meta($value->get_id(), $this->key, true)
        );
    }

}