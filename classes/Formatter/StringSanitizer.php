<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class StringSanitizer implements Formatter
{

    public function format(Value $value): Value
    {
        $formatter = Aggregate::from_array([
            new StripTags(),
            (new PregReplace())
                ->replace_br()
                ->replace_tabs()
                ->replace_new_line()
                ->replace_multiple_spaces(),
            new Trim(),
        ]);

        return $formatter->format($value);
    }

}