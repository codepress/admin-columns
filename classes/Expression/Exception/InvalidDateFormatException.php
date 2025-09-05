<?php

declare(strict_types=1);

namespace AC\Expression\Exception;

use RuntimeException;
use Throwable;

final class InvalidDateFormatException extends RuntimeException
{

    public function __construct(string $date_time, string $format, ?Throwable $previous = null)
    {
        $message = sprintf('Could parse format %s for %s.', $format, $date_time);

        parent::__construct($message, 0, $previous);
    }

}