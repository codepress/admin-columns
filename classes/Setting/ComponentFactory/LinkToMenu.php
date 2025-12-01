<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;

class LinkToMenu extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Link To', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This will make the title link to the menu.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle('link_to_menu', null, 'on' === $config->get('link_to_menu') ? 'on' : 'off');
    }

}