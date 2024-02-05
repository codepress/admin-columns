<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class PostStatusFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new PostStatus(
            $config->has('post_status') ? $config->get('post_status') : null,
            $specification
        );
    }

}