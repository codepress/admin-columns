<?php

namespace AC\ColumnFactory\Post;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\Formatter\Post\FeaturedImage;
use AC\Settings\Column\ImageFactory;

class FeaturedImageFactory implements ColumnFactory
{

    private $builder;

    private $image_factory;

    public function __construct(
        ComponentCollectionBuilder $builder,
        ImageFactory $image_factory
    ) {
        $this->builder = $builder;
        $this->image_factory = $image_factory;
    }

    public function create(Config $config): Column
    {
        $settings = $this->builder->add_defaults()
                                  ->add($this->image_factory)
                                  ->build($config);

        return new Column(
            'column-featured_image',
            __('Featured Image', 'codepress-admin-columns'),
            Aggregate::from_settings($settings)->prepend(new FeaturedImage()),
            $settings
        );
    }

}