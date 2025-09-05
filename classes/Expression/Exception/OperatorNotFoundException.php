<?php

declare(strict_types=1);

namespace AC\Expression\Exception;

use InvalidArgumentException;
use Throwable;

final class OperatorNotFoundException extends InvalidArgumentException
{

    public function __construct(string $operator, ?Throwable $previous = null)
    {
        $message = sprintf('Operator %s was not found.', $operator);

        parent::__construct($message, 0, $previous);
    }

}