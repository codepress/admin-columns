<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\User;

use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Property implements Formatter
{

    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return $value->with_value(
            ac_helper()->user->get_display_name(
                $value->get_id(),
                $this->property
            )
        );
    }

}