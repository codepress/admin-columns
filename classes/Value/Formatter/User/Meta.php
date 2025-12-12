<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Setting\Formatter;
use AC\Type\Value;

class Meta implements Formatter
{

    private string $meta_key;

    public function __construct(string $meta_key)
    {
        $this->meta_key = $meta_key;
    }

    public function format(Value $value): Value
    {
        $meta_value = get_user_meta((int)$value->get_id(), $this->meta_key, true);

        // TODO remove and use the ImplodeRecursiveFormatter
        if (is_array($meta_value)) {
            $meta_value = ac_helper()->array->implode_recursive(', ', $meta_value);
        }

        return $value->with_value(
            $meta_value
        );
    }

}