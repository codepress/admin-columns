<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class WordLimit implements Formatter
{

    private $word_limit;

    public function __construct(int $word_limit)
    {
        $this->word_limit = $word_limit;
    }

    public function format(Value $value): Value
    {
        if ($this->word_limit) {
            $value = $value->with_value(
                ac_helper()->string->trim_words(
                    (string)$value->get_value(),
                    $this->word_limit
                )
            );
        }

        return $value;
    }

}