<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class YesIcon implements Formatter
{

    private ?string $class;

    private ?string $title;

    public function __construct(?string $title = null, ?string $class = null)
    {
        $this->class = $class;
        $this->title = $title;
    }

    public function format(Value $value): Value
    {
        $icon = $value->get_value()
            ? ac_helper()->icon->yes(null, $this->title, $this->class)
            : false;

        return $value->with_value(
            $icon
        );
    }

}