<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\SettingTrait;
use AC\Settings\Column;

abstract class Single extends Column
{

    use SettingTrait;

    public function __construct(AC\Column $column, string $name)
    {
        $this->name = $name;

        parent::__construct($column);
    }

}