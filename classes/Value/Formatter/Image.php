<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
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
        $image = ac_helper()->image->get_image((string)$value, $this->size, $this->skip_image_check);

        if ($image === null) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($image);
    }

}