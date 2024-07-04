<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class Message implements Formatter
{

    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function format(Value $value): Value
    {
        return $value->with_value($this->message);
    }

}