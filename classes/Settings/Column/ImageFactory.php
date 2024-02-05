<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;

class ImageFactory implements AC\Settings\SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new Image(
            $config->has('image_size') ? (string)$config->get('image_size') : null,
            $config->has('image_size_w') ? (int)$config->get('image_size_w') : null,
            $config->has('image_size_h') ? (int)$config->get('image_size_h') : null,
            $specification
        );
    }

}