<?php

namespace AC\ColumnFactory;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\MetaType;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\SettingCollection;
use AC\Settings;
use AC\Vendor\DI\Container;

// TODO POC
class CustomFieldFactory implements ColumnFactory
{

    private $meta_type;

    private $container;

    public function __construct(MetaType $meta_type, Container $container)
    {
        $this->meta_type = $meta_type;
        $this->container = $container;
    }

    public function can_create(string $key): bool
    {
        return 'column-meta' === $key;
    }

    protected function get_settings(Config $config): SettingCollection
    {
        $setting_factory = new Settings\Column\CustomFieldFactory(
            $this->meta_type,
            $this->container->get(Settings\Column\CustomFieldTypeFactory::class)
        );

        return new SettingCollection([
            $setting_factory->create($config),
            $this->container->get(Settings\Column\LabelFactory::class)->create($config),
            $this->container->get(Settings\Column\WidthFactory::class)->create($config),
        ]);
    }

    public function create(Config $config): Column
    {
        $settings = $this->get_settings($config);

        return new Column(
            'column-meta',
            __('Custom Field', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings,
            'custom_field'
        );
    }

}