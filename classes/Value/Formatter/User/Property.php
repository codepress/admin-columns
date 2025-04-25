<?php

declare(strict_types=1);

namespace AC\Value\Formatter\User;

use AC;
use AC\Setting\Formatter;
use AC\Type\Value;

class Property implements Formatter
{

    private ?string $property;

    public function __construct(string $property = null)
    {
        $this->property = $property;
    }

    public function format(Value $value): Value
    {
        $user = get_userdata($value->get_id());

        if ( ! $user) {
            throw AC\Exception\ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $user->{$this->property} ?? null
        );
    }

}