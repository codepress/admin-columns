<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media\Audio;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Bitrate implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->get_value() > 1000
            ? $value->with_value(
                sprintf('%s %s', number_format($value->get_value() / 1000), __('Kbps', 'codepress-admin-columns'))
            )
            : $value;
    }

}