<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input\Custom;
use AC\Setting\SettingTrait;
use AC\Settings\Column;

abstract class Single extends Column
{

    use SettingTrait;

    public function __construct(AC\Column $column, string $name)
    {
        if (null === $this->input) {
            $this->input = new Custom($name);
        }

        $this->name = $name;

        parent::__construct($column);
    }

}