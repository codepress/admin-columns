<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;

class SerializedArrayKeys extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Array Keys', 'codepress-admin-columns');
    }

    // TODO missing instruction modal as in old version

    protected function get_input(Config $config): ?Input
    {
        return new Input\Open(
            'array_keys',
            'text',
            $config->get('array_keys', ''),
            sprintf('%s: %s', __('example', 'codepress-admin-columns'), 'sizes.medium.file')
        );
    }
}