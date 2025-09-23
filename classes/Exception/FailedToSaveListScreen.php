<?php

declare(strict_types=1);

namespace AC\Exception;

use RuntimeException;

final class FailedToSaveListScreen extends RuntimeException
{

    public function __construct(?string $message = null)
    {
        if ($message === null) {
            $message = 'Failed to save list screen.';
        }

        parent::__construct($message);
    }

}