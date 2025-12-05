<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Post;

use AC\Setting\Formatter;
use AC\Type\Value;

class Meta implements Formatter
{

    private string $meta_key;

    private bool $implode;

    public function __construct(string $meta_key, bool $implode = true)
    {
        $this->meta_key = $meta_key;
        $this->implode = $implode;
    }

    public function format(Value $value): Value
    {
        $meta_value = get_post_meta((int)$value->get_id(), $this->meta_key, true);

        if ($this->implode) {
            $meta_value = ac_helper()->array->implode_recursive(', ', $meta_value);
        }

        return $value->with_value(
            $meta_value
        );
    }

}