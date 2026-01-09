<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class CharacterLimit implements Formatter
{

    private int $character_limit;

    public function __construct(int $character_limit)
    {
        $this->character_limit = $character_limit;
    }

    public function format(Value $value): Value
    {
        if ($this->character_limit > 0) {
            return $value->with_value(
                ac_helper()->string->trim_characters(
                    (string)$value,
                    $this->character_limit
                )
            );
        }

        return $value;
    }

}