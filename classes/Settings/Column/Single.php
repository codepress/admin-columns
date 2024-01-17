<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Input;
use AC\Setting\Input\Custom;
use AC\Settings\Column;

abstract class Single extends Column
{

    public function __construct(string $name, string $label, string $description, Input $input = null)
    {
        if (null === $input) {
            $input = new Custom($name);
        }

        parent::__construct($name, $label, $description, $input);
    }

}