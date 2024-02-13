<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\PageTemplate;

class PageTemplateFactory implements ColumnFactory
{

    private $builder;

    private $post_type;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        string $post_type
    ) {
        $this->builder = $builder;
        $this->post_type = $post_type;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->build($config);

        return new Column(
            'column-page_template',
            __('Page Template', 'codepress-admin-columns'),
            new PageTemplate($this->post_type),
            $settings
        );
    }

}