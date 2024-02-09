<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

final class NullFormatter implements Formatter
{

    public function format(Value $value): Value
    {
        return $value;
    }

}