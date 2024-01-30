<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Type\Value;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function format(Value $value): Value
    {
        $settings = new SettingCollection();
        // TODO how to access `get_name`
//        $option = $options->get($this->get_name()) ?: '';

        foreach ($this->get_children() as $setting) {
//            if ($setting->get_conditions()->is_satisfied_by($option)) {
            if ($setting->get_conditions()->is_satisfied_by('')) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings)->format($value);
    }

}