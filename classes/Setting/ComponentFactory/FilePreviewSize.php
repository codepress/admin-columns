<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;

final class FilePreviewSize extends ImageSizeBase
{

    protected function get_size_name(): string
    {
        return 'file_preview_size';
    }

    protected function get_width_name(): string
    {
        return 'file_preview_w';
    }

    protected function get_height_name(): string
    {
        return 'file_preview_h';
    }

    protected function get_default_size(): string
    {
        return 'thumbnail';
    }

    protected function get_label(Config $config): ?string
    {
        return __('Preview size', 'codepress-admin-columns');
    }

}
