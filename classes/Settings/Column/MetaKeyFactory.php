<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\MetaType;
use AC\Setting\Config;
use AC\Settings\Component;
use AC\Settings\SettingFactory;

class MetaKeyFactory implements SettingFactory
{

    private $meta_type;

    public function __construct(MetaType $meta_type)
    {
        $this->meta_type = $meta_type;
    }

    public function create(Config $config, Specification $specification = null): Component
    {
        return new MetaKey(
            (string)$config->get('field'),
            $this->meta_type,
            $specification
        );
    }

}