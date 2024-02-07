<?php

namespace AC\ColumnFactory;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\MetaType;
use AC\Setting\Builder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\SettingCollectionFactory;
use AC\Setting\SettingFactoriesBuilder;
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

    public function can_create(string $type): bool
    {
        return 'column-meta' === $type;
    }

    private function get_setting_factory(): Settings\Column\CustomFieldFactory
    {
        return new Settings\Column\CustomFieldFactory(
            $this->meta_type,
            $this->container->get(Settings\Column\CustomFieldTypeFactory::class)
        );
    }

    public function create(Config $config): Column
    {
        $settings = (new Builder())->set_defaults()
                                   ->set($this->get_setting_factory())
                                   ->build($config);

        return new Column(
            'column-meta',
            __('Custom Field', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings,
            'custom_field'
        );
    }

}