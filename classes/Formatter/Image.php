<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class Image implements Formatter
{

    /**
     * @var string|array
     */
    private $size;

    public function __construct($size)
    {
        $this->size = $size;
    }

    public function format(Value $value): Value
    {
        $image = ac_helper()->image->get_image(
            (string)$value,
            $this->size
        );

        if ($image === null) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($image);
    }

}