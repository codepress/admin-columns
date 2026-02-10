<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

final class WordCount implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            Helper\Strings::create()->word_count($value->get_value())
        );
    }

}