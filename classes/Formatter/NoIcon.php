<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

class NoIcon implements Formatter
{

    private ?string $class;

    public function __construct(?string $class = 'red')
    {
        $this->class = $class;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(Helper\Icon::create()->no(null, null, $this->class));
    }

}