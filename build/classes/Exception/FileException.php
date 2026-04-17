<?php

declare(strict_types=1);

namespace AC\Build\Exception;

use RuntimeException;
use Throwable;

final class FileException extends RuntimeException
{

    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    public static function FileNotReadable(string $file, Throwable $previous = null): self
    {
        $message = sprintf('File %s is not readable', $file);

        return new self($message, $previous);
    }

    public static function FileNotWritable(string $file, Throwable $previous = null): self
    {
        $message = sprintf('File %s is not writable', $file);

        return new self($message, $previous);
    }

}