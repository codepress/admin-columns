<?php

namespace AC\Column;

use AC\Column;
use AC\MetaType;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\Vendor\DI\Container;

/**
 * Custom field column, displaying the contents of meta fields.
 * Suited for all list screens supporting WordPress' default way of handling meta data.
 * Supports different types of meta fields, including dates, serialized data, linked content,
 * and boolean values.
 */
class CustomFieldFactory implements ColumnFactory
{

    private $meta_type;

    private $container;

    public function __construct(MetaType $meta_type, Container $container)
    {
        $this->meta_type = $meta_type;
        $this->container = $container;
    }

    public function create(Config $config): Column
    {
        // TODO inject meta type

        return new CustomField(
            new SettingCollection([
                $this->container->get(Settings\Column\CustomFieldFactory::class)->create($config),
            ])
        );
    }

}