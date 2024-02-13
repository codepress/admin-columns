<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\AggregateBuilder;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): ComponentCollection;

    private function get_recursive_formatter(string $condition): Aggregate
    {
        $builder = new AggregateBuilder();

        foreach ($this->get_children() as $setting) {
            if ($setting instanceof Setting && $setting->get_conditions()->is_satisfied_by($condition)) {
                $builder->add($setting);
            }
        }

        return $builder->build();
    }

}