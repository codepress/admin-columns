<?php

declare(strict_types=1);

namespace AC\Exception;

use AC\Type\ListKey;
use InvalidArgumentException;

final class InvalidListScreenException extends InvalidArgumentException
{

    public static function from_invalid_key(ListKey $key): self
    {
        return new self(sprintf('Invalid key %s', $key));
    }

}