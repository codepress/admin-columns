<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

final class CommentsFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new Comments(
            (string)$config->get('comment_status') ?: 'all',
            $specification
        );
    }

}