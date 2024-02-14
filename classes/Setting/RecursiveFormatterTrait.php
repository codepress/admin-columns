<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\AggregateBuilder;

trait RecursiveFormatterTrait
{

    // TODO Tobias maybe add the Aggregatebuilder and or factory as a property? Now it just exist from a new ()
    abstract public function get_children(): ComponentCollection;

    private function get_recursive_formatter(string $condition = null): Aggregate
    {
        $builder = new AggregateBuilder();

        foreach ($this->get_children() as $setting) {
            if (
                $condition && $setting instanceof Control &&
                ! $setting->get_conditions()->is_satisfied_by($condition)
            ) {
                continue;
            }

            if ($setting instanceof Formatter) {
                $builder->add($setting);
            }
        }

        return $builder->build();
    }

}