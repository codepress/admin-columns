<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;

final class SelectOptions extends Builder
{

    private const NAME = 'select_options';

    protected function get_label(Config $config): ?string
    {
        return __('Select Options', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return new Input\Custom('select_options', self::NAME, [], $config->get(self::NAME));
    }

}