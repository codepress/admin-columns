<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Pro;

use AC\Setting\ComponentFactory\Builder;
use AC\Setting\Config;
use AC\Setting\Control\Input;

class Sorting extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Sorting', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return new Input\Custom('pro_feature', 'pro_feature', [

        ]);
    }

}