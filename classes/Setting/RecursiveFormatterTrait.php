<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): ComponentCollection;

    private function get_recursive_formatter(string $condition): Aggregate
    {
        $settings = new ComponentCollection();

        foreach ($this->get_children() as $setting) {
            if ($setting instanceof Setting && $setting->get_conditions()->is_satisfied_by($condition)) {
                $settings->add($setting);
            }
        }

        return Aggregate::from_settings($settings);
    }

}