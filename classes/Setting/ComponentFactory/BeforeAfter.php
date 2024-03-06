<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control;
use AC\Setting\Control\Input\Open;
use AC\Setting\Formatter;

final class BeforeAfter implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        return new Component(
            __('Display Options', 'codepress-admin-columns'),
            null,
            null,
            new Formatter\BeforeAfter(
                $config->get('before'),
                $config->get('after')
            ),
            new Children(
                new ComponentCollection([
                    new Component(
                        __('Before', 'codepress-admin-columns'),
                        null,
                        new Control(
                            new Open('before', null, $config->get('before'))
                        )
                    ),
                    new Component(
                        __('After', 'codepress-admin-columns'),
                        null,
                        new Control(
                            new Open('after', null, $config->get('after'))
                        )
                    ),
                ])
            )
        );
    }

}