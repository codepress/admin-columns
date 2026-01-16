<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Setting\Config;
use AC\Type\Value;

final class BeforeAfter implements Formatter
{

    private string $before;

    private string $after;

    public function __construct(?string $before = null, ?string $after = null)
    {
        $this->before = (string)$before;
        $this->after = (string)$after;
    }

    public function format(Value $value): Value
    {
        if ('' === (string)$value) {
            return $value;
        }

        return $value->with_value(
            $this->before . (string)$value . $this->after
        );
    }

    public static function create_from_config(Config $config): BeforeAfter
    {
        return new self(
            $config->has('before') ? (string)$config->get('before', '') : null,
            $config->has('after') ? (string)$config->get('after', '') : null,
        );
    }

}