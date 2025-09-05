<?php

declare(strict_types=1);

namespace AC\Expression\Exception;

use InvalidArgumentException;
use Throwable;

final class SpecificationNotFoundException extends InvalidArgumentException
{

    public function __construct(string $specification, ?Throwable $previous = null)
    {
        $message = sprintf('Specification %s was not found.', $specification);

        parent::__construct($message, 0, $previous);
    }

}