<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class FileMetaVideoFormatFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new FileMetaVideo(
            (string)$config->get('media_meta_key') ?: 'dataformat',
            $specification
        );
    }

}