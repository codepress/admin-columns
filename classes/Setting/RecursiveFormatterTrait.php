<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Type\Value;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $settings = [];

        foreach ($this->get_children() as $setting) {
            if (
                ! $setting->has_conditions() ||
                $setting->get_conditions()->is_satisfied_by($options->get($this->name))
            ) {
                $settings[] = $setting;
            }
        }

        return Aggregate::from_settings(new SettingCollection($settings))->format($value, $options);
    }

}