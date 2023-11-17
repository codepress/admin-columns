<?php

declare(strict_types=1);

namespace AC\Setting\Setting;

use AC\Setting\ConditionCollection;
use AC\Setting\Input;
use AC\Setting\Setting;
use AC\Setting\SettingTrait;

class Custom implements Setting
{

    use SettingTrait;

    public function __construct(
        string $name,
        string $label = '',
        string $description = '',
        Input $input = null,
        ConditionCollection $conditions = null
    ) {
        if (null === $input) {
            $input = new Input\Custom($name);
        }

        if (null === $conditions) {
            $conditions = new ConditionCollection();
        }

        $this->name = $name;
        $this->label = $label;
        $this->description = $description;
        $this->input = $input;
        $this->conditions = $conditions;
    }

}