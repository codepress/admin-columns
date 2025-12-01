<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

abstract class BaseColumnFactory extends ColumnFactory
{

    use GroupTrait;

    private DefaultSettingsBuilder $default_settings_builder;

    public function __construct(DefaultSettingsBuilder $default_settings_builder)
    {
        $this->default_settings_builder = $default_settings_builder;
    }

    public function create(Config $config): Column
    {
        return new Base(
            $this->get_column_type(),
            $this->get_label(),
            $this->default_settings_builder->build($config)
                                           ->merge($this->get_settings($config)),
            $this->resolve_id($config),
            $this->get_context($config),
            $this->get_formatters($config),
            $this->get_group()
        );
    }

}