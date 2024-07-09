<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;

class ImageFactory extends BaseColumnFactory
{

    private $image_size;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ImageSize $image_size
    ) {
        parent::__construct($component_factory_registry);

        $this->image_size = $image_size;
    }

    protected function add_component_factories(Config $config): void
    {
        $this->add_component_factory($this->image_size);

        parent::add_component_factories($config);
    }

    public function get_column_type(): string
    {
        return 'column-image';
    }

    public function get_label(): string
    {
        return __('Image', 'codepress-admin-columns');
    }

    protected function get_group(): ?string
    {
        return 'media-image';
    }

}