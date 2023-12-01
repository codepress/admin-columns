<?php

declare(strict_types=1);

namespace AC\Exception;

use BadMethodCallException;

final class MissingFormatterException extends BadMethodCallException
{

    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Missing formatter.', $code, $previous);
    }

}