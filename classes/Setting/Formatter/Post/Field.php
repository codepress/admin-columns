<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Field implements Formatter
{

    private $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_post_field($this->field, $value->get_id())
        );
    }

}