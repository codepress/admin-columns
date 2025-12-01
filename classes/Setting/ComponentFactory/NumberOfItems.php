<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;

class NumberOfItems extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Number of Items', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return sprintf(
            '%s <em>%s</em>',
            __('Maximum number of items', 'codepress-admin-columns'),
            __('Leave empty for no limit', 'codepress-admin-columns')
        );
    }

    protected function get_input(Config $config): ?Input
    {
        $number_of_items = $config->has('number_of_items') ? (int)$config->get('number_of_items') : 10;

        return Number::create_single_step('number_of_items', 0, null, $number_of_items);
    }
}