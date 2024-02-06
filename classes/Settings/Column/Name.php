<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\Input;

// TODO this seems a bit of a useless class? A dedicated factory can just create this
class Name extends Input
{

    public function __construct(string $name)
    {
        parent::__construct(
            'name',
            'hidden',
            $name
        );
    }
}