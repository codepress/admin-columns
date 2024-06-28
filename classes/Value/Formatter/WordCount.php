<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class WordCount implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->string->word_count($value->get_value())
        );
    }

}