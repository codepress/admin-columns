<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Meta implements Formatter
{

    private $meta_key;

    public function __construct(string $meta_key)
    {
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(get_post_meta((int)$value->get_id(), $this->meta_key, true));
    }

}