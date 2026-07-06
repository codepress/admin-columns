<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class StringSanitizer implements Formatter
{
    public function format(Value $value): Value
    {
        $formatter = Aggregate::from_array([
            (new PregReplace())
                ->replace_br()
                ->replace_non_breaking_space(),
            new StripTags(),
            (new PregReplace())
                ->replace_tabs()
                ->replace_new_line()
                ->replace_multiple_spaces(),
            new Trim(),
        ]);

        $formatted = $formatter->format($value);

        if (! $formatted instanceof Value) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $formatted;
    }

}
