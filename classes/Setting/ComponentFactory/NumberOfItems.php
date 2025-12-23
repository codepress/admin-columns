<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;

class NumberOfItems extends BaseComponentFactory implements InputNameAware
{

    public function get_name(): string
    {
        return 'number_of_items';
    }

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
        $value = $config->has($this->get_name())
            ? $config->get($this->get_name())
            : 10;

        return Number::create_single_step($this->get_name(), 0, null, (int)$value);
    }

}