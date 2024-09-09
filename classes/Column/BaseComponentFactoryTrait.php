<?php

namespace AC\Column;

use AC\Setting\ComponentCollection;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

trait BaseComponentFactoryTrait
{

    private function add_component_formatters(FormatterCollection $formatters, ComponentCollection $components): void
    {
        foreach ($components as $component) {
            foreach ($component->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }
    }

    private function add_components(
        ComponentCollection $components,
        ConditionalComponentFactoryCollection $factories,
        Config $config
    ): void {
        foreach ($factories as $component_factory) {
            $components->add($component_factory->create($config));
        }
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
    }

    protected function get_group(): ?string
    {
        return null;
    }

}