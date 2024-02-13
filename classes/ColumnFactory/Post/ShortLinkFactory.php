<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Linkable;
use AC\Setting\Formatter\Post\ShortLink;

class ShortLinkFactory implements ColumnFactory
{

    private $builder;

    public function __construct(
        ComponentCollectionBuilderFactory $builder
    ) {
        $this->builder = $builder;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->build($config);

        $formatter = new Aggregate([
            new ShortLink(),
            new Linkable(),
        ]);

        return new Column(
            'column-shortlink',
            __('Shortlink', 'codepress-admin-columns'),
            $formatter,
            $settings
        );
    }

}