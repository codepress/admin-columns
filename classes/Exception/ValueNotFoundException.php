<?php

declare(strict_types=1);

namespace AC\Exception;

use RuntimeException;

final class ValueNotFoundException extends RuntimeException
{

    public static function from_id($id): self
    {
        $message = sprintf('Value for id %s was not found.', $id);

        return new self($message);
    }

}