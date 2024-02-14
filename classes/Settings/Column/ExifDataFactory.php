<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

final class ExifDataFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new ExifData(
            __('Image Meta (EXIF)', 'codepress-admin-columns'),
            $config->get('exif_data') ?: 'aperture',
            $specification
        );
    }

}