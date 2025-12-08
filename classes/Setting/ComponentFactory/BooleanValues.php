<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class BooleanValues extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Boolean Values', 'codepress-admin-columns');
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                new Component(
                    __('Value for <code>false</code> ', 'codepress-admin-columns'),
                    null,
                    new Input\Open(
                        'boolean_value_off',
                        null,
                        $config->get('boolean_value_off', '0')
                    )
                ),
                new Component(
                    __('Value for <code>true</code>', 'codepress-admin-columns'),
                    null,
                    new Input\Open(
                        'boolean_value_on',
                        null,
                        $config->get('boolean_value_on', '1')
                    )
                ),
            ]), true
        );
    }

}