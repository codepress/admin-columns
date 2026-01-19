<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class Shortcodes implements Formatter
{

    public function format(Value $value): Value
    {
        $shortcodes = ac_helper()->string->get_shortcodes(
            (string)$value
        );

        if ( ! $shortcodes) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $display = [];

        foreach ($shortcodes as $sc => $count) {
            $string = '[' . $sc . ']';

            if ($count > 1) {
                $string .= ac_helper()->html->rounded((string)$count);
            }

            $display[$sc] = '<span class="ac-spacing">' . $string . '</span>';
        }

        return $value->with_value(implode(' ', $display));
    }

}