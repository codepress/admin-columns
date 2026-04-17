<?php

declare(strict_types=1);

namespace AC\Build\Exception;

use RuntimeException;
use Throwable;

final class DirectoryException extends RuntimeException
{

    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function DirectoryDoesNotExist(string $directory, Throwable $previous = null): self
    {
        $message = sprintf('Directory %s does not exist', $directory);

        return new self($message, $previous);
    }

    public static function DirectoryNotReadable(string $directory, Throwable $previous = null): self
    {
        $message = sprintf('Directory %s is not readable', $directory);

        return new self($message, $previous);
    }

    public static function DirectoryNotCreated(string $directory, Throwable $previous = null): self
    {
        $message = sprintf('Could not create directory %s', $directory);

        return new self($message, $previous);
    }

}