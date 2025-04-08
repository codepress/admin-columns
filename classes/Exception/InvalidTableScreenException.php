<?php

declare(strict_types=1);

namespace AC\Exception;

use AC\Type\TableId;
use InvalidArgumentException;

final class InvalidTableScreenException extends InvalidArgumentException
{

    public static function from_invalid_id(TableId $id): self
    {
        return new self(sprintf('Invalid table id %s', $id));
    }

}