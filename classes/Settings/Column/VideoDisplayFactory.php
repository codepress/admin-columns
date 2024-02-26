<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class VideoDisplayFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new VideoDisplay(
            (string)$config->get('video_display') ?: 'embed'
        );
    }

}