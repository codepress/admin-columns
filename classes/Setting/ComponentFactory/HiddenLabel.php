<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;

final class HiddenLabel extends BaseComponentFactory
{

    protected function get_input(Config $config): ?Input
    {
        $label = $config->has('label')
            ? (string)$config->get('label')
            : '';

        return new Input(
            'label',
            'hidden',
            $label
        );
    }

    protected function get_type(Config $config): ?string
    {
        return 'input_only';
    }

}