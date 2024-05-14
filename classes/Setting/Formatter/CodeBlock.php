<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class CodeBlock implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->html->codearea((string)$value)
        );
    }

}