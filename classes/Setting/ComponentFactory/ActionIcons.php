<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;

final class ActionIcons implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        return new Component(
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            OptionFactory::create_toggle(
                'use_icons',
                null,
                $config->get('use_icons') === 'on' ? 'on' : 'off'
            ),
            $conditions
        );
    }

}