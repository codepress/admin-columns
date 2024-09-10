<?php

declare(strict_types=1);

namespace AC\Expression\Exception;

use RuntimeException;
use Throwable;

final class InvalidDateFormatException extends RuntimeException
{

    public function __construct(string $date, string $format, Throwable $previous = null)
    {
        $message = sprintf('Could not create date with format %s from date %s.', $format, $date);

        parent::__construct($message, 0, $previous);
    }

}