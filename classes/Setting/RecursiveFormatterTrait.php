<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Type\Value;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function format_by_condition(Value $value, string $condition): Value
    {
        $settings = new SettingCollection();

        foreach ($this->get_children() as $setting) {
            if ($setting->get_conditions()->is_satisfied_by($condition)) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings)->format($value);
    }

}