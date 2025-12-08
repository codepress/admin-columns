<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class Avatar implements Formatter
{

    private $size;

    public function __construct(int $size = 60)
    {
        $this->size = $size;
    }

    public function format(Value $value): Value
    {
        $image = get_avatar(
            $value->get_value(),
            $this->size
        );

        if ( ! $image) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($image);
    }

}