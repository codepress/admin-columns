<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;

final class ActionIcons extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Use icons?', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle(
            'use_icons',
            null,
            $config->get('use_icons') === 'on' ? 'on' : 'off'
        );
    }

}