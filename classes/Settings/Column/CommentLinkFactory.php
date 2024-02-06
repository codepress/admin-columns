<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

final class CommentLinkFactory implements SettingFactory
{

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new CommentLink(
            (string)$config->get('comment_link_to') ?: null,
            $specification
        );
    }

}