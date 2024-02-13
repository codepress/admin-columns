<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\PostStatus;
use AC\Settings;

class StatusFactory implements ColumnFactory
{

    private $builder;

    private $status_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        Settings\Column\PostStatusIconFactory $status_icon_factory
    ) {
        $this->builder = $builder;
        $this->status_factory = $status_icon_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->status_factory)
                                  ->build($config);

        return new Column(
            'column-status',
            __('Status', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new PostStatus()),
            $settings
        );
    }

}