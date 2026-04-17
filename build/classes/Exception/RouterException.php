<?php

declare(strict_types=1);

namespace AC\Build\Exception;

use LogicException;
use Throwable;

final class RouterException extends LogicException
{

    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function missingTask(): self
    {
        return new self('Missing task');
    }

    public static function taskNotDefined(string $task): self
    {
        return new self(sprintf('Task %s is not defined', $task));
    }

}