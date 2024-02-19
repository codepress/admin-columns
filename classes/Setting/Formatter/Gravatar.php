<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class Gravatar implements Formatter
{

    private $size;

    public function __construct(int $size = 60)
    {
        $this->size = $size;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(get_avatar($value->get_value(), $this->size));
    }

}