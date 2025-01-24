<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;

final class IsMultiple extends Builder
{

    private bool $default_multiple;

    public function __construct(bool $default_multiple = false)
    {
        $this->default_multiple = $default_multiple;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Multiple', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        $default_mapped_value = $this->default_multiple ? 'on' : 'off';

        return OptionFactory::create_toggle(
            'is_multiple',
            null,
            $config->get('is_multiple') === 'on' ? 'on' : $default_mapped_value,
        );
    }

}