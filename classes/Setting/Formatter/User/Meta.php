<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Meta implements Formatter
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return $value->with_value(
            get_user_meta($value->get_id(), $this->key, true)
        );
    }

}