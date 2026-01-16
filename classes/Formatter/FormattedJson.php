<?php

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class FormattedJson implements Formatter
{

    private array $keys;

    public function __construct(array $keys = [])
    {
        $this->keys = $keys;
    }

    public function format(Value $value)
    {
        $array = $value->get_value();

        if ( ! is_array($array)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $formatted_value = ac_helper()->array->get_nested_value(
            $array,
            $this->keys
        );

        return $value->with_value(
            sprintf(
                '<div data-component="ac-json" data-json="%s" data-level="1" ></div>',
                esc_attr(json_encode($formatted_value))
            )
        );
    }

}