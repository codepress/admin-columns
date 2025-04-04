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
            $this->get_settings($config)
                 ->merge($this->default_settings_builder->build($config)),
            ColumnIdFactory::createFromConfig($config),
            $this->get_formatters($config),
            $this->get_group()
        );
    }

}