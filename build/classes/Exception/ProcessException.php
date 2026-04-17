<?php

declare(strict_types=1);

namespace AC\Build\Exception;

use RuntimeException;
use Throwable;

final class ProcessException extends RuntimeException
{

    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function processUnsuccessful(string $command, Throwable $previous = null): self
    {
        $message = sprintf('Process for command %s failed', $command);

        return new self($message, $previous);
    }

}