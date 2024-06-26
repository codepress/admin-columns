<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC\Setting\Formatter;
use AC\Type\Value;

class Meta implements Formatter
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_user_meta((int)$value->get_id(), $this->key, true)
        );
    }

}