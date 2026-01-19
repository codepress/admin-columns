<?php

declare(strict_types=1);

namespace AC\Formatter\User;

use AC\Formatter;
use AC\Type\Value;

class HasRichEditing implements Formatter
{

    public function format(Value $value): Value
    {
        return new Value('true' === get_userdata($value->get_id())->rich_editing);
    }

}