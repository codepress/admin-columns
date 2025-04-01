<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class BaseColumnFactory extends ColumnFactory
{

    protected BaseSettingsBuilder $base_settings_builder;

    public function __construct(BaseSettingsBuilder $base_settings_builder)
    {
        $this->base_settings_builder = $base_settings_builder;
    }

    protected function get_default_settings(Config $config): ComponentCollection
    {
        return $this->base_settings_builder->build($config);
    }

    // TODO David remove
    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
    }

    // TODO David remove
    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
    }

}