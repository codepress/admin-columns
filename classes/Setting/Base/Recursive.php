<?php

declare(strict_types=1);

namespace AC\Setting\Base;

use AC;
use AC\Setting\ConditionCollection;
use AC\Setting\Input;
use AC\Setting\RecursiveTrait;
use AC\Setting\SettingCollection;
use AC\Setting\SettingTrait;

class Recursive implements AC\Setting\Recursive
{

    use SettingTrait;
    use RecursiveTrait;

    private $settings;

    public function __construct(
        string $name,
        SettingCollection $settings,
        string $label = '',
        string $description = '',
        ConditionCollection $conditions = null
    ) {
        if (null === $this->conditions) {
            $conditions = new ConditionCollection();
        }

        $this->name = $name;
        $this->settings = $settings;
        $this->label = $label;
        $this->description = $description;
        $this->conditions = $conditions;
        $this->input = new Input\Custom($name);
    }

    public function get_children(): SettingCollection
    {
        return $this->settings;
    }

}