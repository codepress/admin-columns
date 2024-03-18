<?php

namespace AC\ColumnFactory;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter\Post\FeaturedImage;

class RemoveMeFactory extends ColumnFactory
{

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ImageSize $image_size
    ) {
        parent::__construct($component_factory_registry);

        $this->add_component_factory($image_size);
    }

    protected function get_formatters(ComponentCollection $components): array
    {
        return array_merge([
            new FeaturedImage(),
        ],
            parent::get_formatters($components)
        );
    }

    public function get_type(): string
    {
        return 'column-removeme';
    }

    protected function get_label(): string
    {
        return __('Remove', 'codepress-admin-columns');
    }

}