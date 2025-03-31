<?php

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;

class ImageFactory extends BaseColumnFactory
{

    private $image_size;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ImageSize $image_size
    ) {
        parent::__construct($base_settings_builder);

        $this->image_size = $image_size;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->image_size);
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