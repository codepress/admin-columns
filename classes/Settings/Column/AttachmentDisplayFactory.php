<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Column;
use AC\Settings\SettingFactory;

class AttachmentDisplayFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Column
    {
        return new AttachmentDisplay((string)$config->get('attachment_display'), $specification);
    }

}