<?php

namespace AC\ColumnFactory;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\MetaType;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings;
use AC\Vendor\DI\Container;

class CustomFieldFactory implements ColumnFactory
{

    private $meta_type;

    private $container;

    private $builder;

    public function __construct(MetaType $meta_type, Container $container, ComponentCollectionBuilder $builder)
    {
        $this->meta_type = $meta_type;
        $this->container = $container;
        $this->builder = $builder;
    }

    public function create(Config $config): Column
    {
        $factory = new Settings\Column\CustomFieldFactory(
            $this->meta_type,
            $this->container->get(Settings\Column\CustomFieldTypeFactory::class)
        );

        $settings = $this->builder->add_defaults()
                                  ->add($factory)
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