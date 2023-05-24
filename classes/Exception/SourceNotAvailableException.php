<?php

declare(strict_types=1);

namespace AC\Exception;

use LogicException;

final class SourceNotAvailableException extends LogicException
{

    public function __construct($code = 0)
    {
        parent::__construct('No source available.', $code);
    }

}