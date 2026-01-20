<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Media;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\ImageSize;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;

class ImageFactory extends BaseColumnFactory
{

    private ImageSize $image_size;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ImageSize $image_size
    ) {
        parent::__construct($default_settings_builder);

        $this->image_size = $image_size;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->image_size->create($config),
        ]);
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