<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class BaseColumnFactory implements ColumnFactory
{

    protected BaseSettingsBuilder $base_settings_builder;

    public function __construct(BaseSettingsBuilder $base_settings_builder)
    {
        $this->base_settings_builder = $base_settings_builder;
    }

    protected function add_component_formatters(FormatterCollection $formatters, ComponentCollection $components): void
    {
        foreach ($components as $component) {
            foreach ($component->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }
    }

    private function get_formatters_from_settings(ComponentCollection $settings): FormatterCollection
    {
        $formatters = new FormatterCollection();

        foreach ($settings as $setting) {
            foreach ($setting->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }

        return $formatters;
    }

    protected function get_settings(Config $config) : ComponentCollection
    {
        return new ComponentCollection();
    }

    protected function get_formatters( Config $config ): FormatterCollection
    {
        return $this->get_formatters_from_settings($this->get_settings($config));
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

    // TODO Stefan FIX BOY, NOW or later or never
    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
    }

    protected function get_group(): ?string
    {
        return null;
    }

    public function create(Config $config): Column
    {
        $components = $this->base_settings_builder->build($config);
        $factories = new ConditionalComponentFactoryCollection();
        $formatters = new FormatterCollection();

        $this->add_component_factories($factories);

        $this->add_components($components, $factories, $config);
        $this->add_component_formatters($formatters, $components);
        $this->add_formatters($formatters, $config);

        return new Column\Base(
            $this->get_column_type(),
            $this->get_label(),
            $components,
            ColumnIdFactory::createFromConfig($config),
            $formatters,
            $this->get_group(),
        );
    }

}