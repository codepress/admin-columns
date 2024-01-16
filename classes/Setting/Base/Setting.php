<?php

declare(strict_types=1);

namespace AC\Setting\Base;

use AC;
use AC\Setting\Input;
use AC\Setting\SettingTrait;
use AC\Expression\Specification;

class Setting implements AC\Setting\Setting
{

    use SettingTrait;

    public function __construct(
        string $name,
        string $label = '',
        string $description = '',
        Input $input = null,
        Specification $conditions = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->conditions = $conditions;
    }

}