<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilderFactory;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\PostParent;
use AC\Settings\Column\PostFactory;
use AC\Settings\Column\PostLinkFactory;

class ParentFactory implements ColumnFactory
{

    private $builder;

    private $post_factory;

    private $post_link_factory;

    public function __construct(
        ComponentCollectionBuilderFactory $builder,
        PostFactory $post_factory
    ) {
        $this->builder = $builder;
        $this->post_factory = $post_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->create()
                                  ->add_defaults()
                                  ->add($this->post_factory)
                                  ->add(new PostLinkFactory(null))
                                  ->build($config);

        return new Column(
            'column-parent',
            __('Parent', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new PostParent()),
            $settings
        );
    }

}