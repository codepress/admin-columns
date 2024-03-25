<?php

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class FormattedJson implements Formatter
{

    public function format(Value $value)
    {
        $array = $value->get_value();

        if ( ! is_array($array)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            sprintf(
                '<div data-component="ac-json" data-json="%s" ></div>',
                esc_attr(json_encode($array))
            )
        );
    }

}