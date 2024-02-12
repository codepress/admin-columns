<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\PostFormat;
use AC\Settings\Column\PostFormatIconFactory;

class FormatsFactory implements ColumnFactory
{

    private $builder;

    private $post_format_icon_format;

    public function __construct(
        ComponentCollectionBuilder $builder,
        PostFormatIconFactory $post_format_icon_format
    ) {
        $this->builder = $builder;
        $this->post_format_icon_format = $post_format_icon_format;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->add($this->post_format_icon_format)
                                  ->build($config);

        //TPDP
        return new Column(
            'column-post_formats',
            __('Post Format', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new PostFormat()),
            $settings
        );
    }

}