<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Settings\Column\BeforeAfterFactory;

class IdFactory implements ColumnFactory
{

    private $builder;

    private $before_after_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        BeforeAfterFactory $before_after_factory
    ) {
        $this->builder = $builder;
        $this->before_after_factory = $before_after_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->add($this->before_after_factory)
                                  ->build($config);

        return new Column(
            'column-postid',
            __('ID', 'codepress-admin-columns'),
            Aggregate::from_settings($settings),
            $settings
        );
    }

}