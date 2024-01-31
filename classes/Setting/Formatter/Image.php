<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Image implements Formatter
{

    /**
     * @var string|array
     */
    private $size;

    private $skip_image_check;

    public function __construct($size, bool $skip_image_check = false)
    {
        $this->size = $size;
        $this->skip_image_check = $skip_image_check;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->image->get_image($value, $this->size, $this->skip_image_check)
        );
    }

}