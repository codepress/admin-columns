<?php

declare(strict_types=1);

namespace AC\Formatter\Media\Audio;

use AC\Formatter;
use AC\Type\Value;

class Channels implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->get_value() > 0
            ? $value->with_value(
                sprintf(
                    '%s %s',
                    number_format($value->get_value()),
                    _n('channel', 'channels', $value->get_value(), 'codepress-admin-columns')
                )
            )
            : $value;
    }

}