<?php

declare(strict_types=1);

namespace AC\Setting\Base;

use AC;
use AC\Setting\SettingTrait;
use ACP\Expression\Specification;

class Setting implements AC\Setting\Setting
{

    use SettingTrait;

    public function __construct(
        string $name,
        string $label = '',
        string $description = '',
        Element $input = null,
        Specification $conditions = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->conditions = $conditions;
    }

}