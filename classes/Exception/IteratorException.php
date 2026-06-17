<?php

declare(strict_types=1);

namespace AC\Exception;

use BadMethodCallException;

final class IteratorException extends BadMethodCallException
{
    public static function from_current(): self
    {
        return new self('The iterator is not positioned on a valid element. Call valid() before current().');
    }

    public static function from_key(): self
    {
        return new self('The iterator is not positioned on a valid key. Call valid() before key().');
    }

}