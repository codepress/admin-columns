<?php

declare(strict_types=1);

namespace AC\Exception;

use AC\Vendor\Psr\Container\ContainerExceptionInterface;
use LogicException;

final class ContainerException extends LogicException implements ContainerExceptionInterface
{

    public static function from_locked(): self
    {
        return new self('Container cannot be modified after it has been built.');
    }

}