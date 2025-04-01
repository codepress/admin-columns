<?php

namespace AC\Column;

use AC\Column;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class ColumnFactory
{

    private ?DefaultSettingsBuilder $default_settings_builder;

    public function __construct(DefaultSettingsBuilder $default_settings_builder = null)
    {
        $this->default_settings_builder = $default_settings_builder;
    }

    public function create(Config $config): Column
    {
        return new Base(
            $this->get_column_type(),
            $this->get_label(),
            $this->get_default_settings($config)
                 ->merge($this->get_settings($config)),
            ColumnIdFactory::createFromConfig($config),
            $this->get_formatters($config),
            $this->get_group()
        );
    }

    protected function get_default_settings(Config $config): ComponentCollection
    {
        return $this->default_settings_builder
            ? $this->default_settings_builder->build($config)
            : new ComponentCollection();
    }

    abstract public function get_column_type(): string;

    abstract public function get_label(): string;

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection();
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return $this->get_formatters_from_settings(
            $this->get_settings($config)
        );
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

    protected function get_group(): ?string
    {
        return null;
    }

}