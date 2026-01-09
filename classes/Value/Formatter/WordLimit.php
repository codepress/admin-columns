<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

final class WordLimit implements Formatter
{

    private int $word_limit;

    public function __construct(int $word_limit)
    {
        $this->word_limit = $word_limit;
    }

    public function format(Value $value): Value
    {
        $string = $value->get_value();

        if ( ! is_scalar($string)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if ($this->word_limit > 0 && '' !== $string) {
            $value = $value->with_value(
                wp_trim_words(
                    (string)$string,
                    $this->word_limit
                )
            );
        }

        return $value;
    }

}