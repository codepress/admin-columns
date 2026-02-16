<?php

declare(strict_types=1);

namespace AC\Exception;

use BadFunctionCallException;

final class HookTimingException extends BadFunctionCallException
{

    public static function called_too_early(string $hook_name): self
    {
        return new self(sprintf('Call this after the `%s` hook.', $hook_name));
    }

    public static function called_too_late(string $hook_name): self
    {
        return new self(sprintf('Call this before the `%s` hook triggers.', $hook_name));
    }

}