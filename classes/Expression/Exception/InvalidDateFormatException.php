<?php

declare(strict_types=1);

namespace AC\Expression\Exception;

use RuntimeException;
use Throwable;

final class InvalidDateFormatException extends RuntimeException
{

    public function __construct(string $date, string $format, $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Could not create date with format %s from date %s.', $format, $date);

        parent::__construct($message, $code, $previous);
    }

}