<?php

declare(strict_types=1);

namespace AC\Exception;

use RuntimeException;

final class ValueNotFoundException extends RuntimeException
{

    public static function from_id($id): self
    {
        $message = is_scalar($id)
            ? sprintf('Value for id %s was not found.', $id)
            : 'Value was not found.';

        return new self($message);
    }

}