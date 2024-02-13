<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\ModifiedDate;
use AC\Settings\Column\DateFactory;

class LastModifiedFactory implements ColumnFactory
{

    private $builder;

    private $date_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        DateFactory $date_factory
    ) {
        $this->builder = $builder;
        $this->date_factory = $date_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->add($this->date_factory)
                                  ->build($config);

        return new Column(
            'column-modified',
            __('Last Modified', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new ModifiedDate()),
            $settings
        );
    }

}