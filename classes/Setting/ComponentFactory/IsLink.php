<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;

final class IsLink extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Display as link', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle(
            'is_link',
            null,
            $config->get('is_link') === 'on' ? 'on' : 'off'
        );
    }

}