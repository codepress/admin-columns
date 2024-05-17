<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\FormatterCollection;

abstract class Base implements ComponentFactory
{

    protected function get_formatters_recursive(
        ComponentCollection $components,
        FormatterCollection $formatters,
        string $condition = null
    ):
    FormatterCollection {
        foreach ($components as $component) {
            if ($component->get_conditions()->is_satisfied_by((string)$condition)) {
                foreach ($component->get_formatters() as $formatter) {
                    $formatters->add($formatter);
                }
            }
        }

        return $formatters;
    }

}