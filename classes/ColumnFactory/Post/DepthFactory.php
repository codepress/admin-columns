<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter;

class DepthFactory implements ColumnFactory
{

    private $builder;

    public function __construct(
        ComponentCollectionBuilderFactory $builder
    ) {
        $this->builder = $builder;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()->add_defaults()
                                  ->build($config);

        return new Column(
            'column-depth',
            __('Depth', 'codepress-admin-columns'),
            new Formatter\Post\Depth(),
            $settings
        );
    }

}