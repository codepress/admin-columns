<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Type\Value;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function format(Value $value, Config $options): Value
    {
        $settings = new SettingCollection();
        $option = $options->get($this->get_name()) ?: '';

        foreach ($this->get_children() as $setting) {
            if ($setting->get_conditions()->is_satisfied_by($option)) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings)->format($value, $options);
    }

}