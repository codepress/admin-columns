<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Control;
use AC\Settings\SettingFactory;

final class FileMetaAudioFormatFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new FileMetaAudio(
            (string)$config->get('media_meta_key') ?: 'dataformat',
            $specification
        );
    }

}