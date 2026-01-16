<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Type\Value;

class EmptyValue implements Formatter
{

    public const DEFAULT = '&ndash;';

    private ?string $default;

    public function __construct(?string $default = null)
    {
        $this->default = $default ?? self::DEFAULT;
    }

    public function format(Value $value): Value
    {
        if ('' === (string)$value) {
            return $value->with_value($this->default);
        }

        return $value;
    }

}