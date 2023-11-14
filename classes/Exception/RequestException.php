<?php

namespace AC\Exception;

use RuntimeException;

class RequestException extends RuntimeException
{

    public static function parameters_invalid(): self
    {
        return new self('Invalid request parameters.');
    }

}