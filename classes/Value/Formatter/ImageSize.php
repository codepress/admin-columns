<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class ImageSize implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->image->get_local_image_size((string)$value)
        );
    }

}