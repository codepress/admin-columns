<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

class Image implements Formatter
{

    /**
     * @var string|array
     */
    private $size;

    private bool $skip_image_check;

    public function __construct($size, bool $skip_image_check = false)
    {
        $this->size = $size;
        $this->skip_image_check = $skip_image_check;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->image->get_image($value->get_value(), $this->size, $this->skip_image_check)
        );
    }

}