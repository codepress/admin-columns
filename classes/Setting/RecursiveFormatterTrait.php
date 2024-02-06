<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function get_recursive_formatter(string $condition): Aggregate
    {
        $settings = new SettingCollection();

        foreach ($this->get_children() as $setting) {
            // TODO expect Setting not Component
            if (  $setting->get_conditions()->is_satisfied_by($condition)) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings);
    }

}