<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class PostTypeFactory implements SettingFactory
{

    private $show_all;

    public function __construct(bool $show_all)
    {
        $this->show_all = $show_all;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new PostType(
            $config->has('post_type') ? $config->get('post_type') : null,
            $this->show_all,
            $specification
        );
    }

}