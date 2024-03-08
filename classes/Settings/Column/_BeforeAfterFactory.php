<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class BeforeAfterFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Component
    {
        return new BeforeAfter(
            $config->has('before') ? (string)$config->get('before') : null,
            $config->has('after') ? (string)$config->get('after') : null
        );
    }

}