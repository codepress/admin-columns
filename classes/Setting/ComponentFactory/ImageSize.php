<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Formatter\Image;
use AC\FormatterCollection;
use AC\Setting\Config;

final class ImageSize extends ImageSizeBase
{

    protected function get_size_name(): string
    {
        return 'image_size';
    }

    protected function get_width_name(): string
    {
        return 'image_size_w';
    }

    protected function get_height_name(): string
    {
        return 'image_size_h';
    }

    protected function get_label(Config $config): ?string
    {
        return __('Image size', 'codepress-admin-columns');
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $formatters->add(new Image($this->get_size($config)));
    }

}
