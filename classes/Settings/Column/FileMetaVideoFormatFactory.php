<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

final class FileMetaVideoFormatFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new FileMetaVideo(
            (string)$config->get('media_meta_key') ?: 'dataformat',
            $specification
        );
    }

}