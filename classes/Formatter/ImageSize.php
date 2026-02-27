<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

class ImageSize implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            Helper\Image::create()->get_local_image_size((string)$value)
        );
    }

}