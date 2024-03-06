<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Open;
use AC\Setting\Formatter;

final class BeforeAfter implements ComponentFactory
{

    private const BEFORE = 'before';
    private const AFTER = 'after';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $before = $config->get(self::BEFORE);
        $after = $config->get(self::AFTER);

        return new Component(
            __('Display Options', 'codepress-admin-columns'),
            null,
            null,
            null,
            new Formatter\BeforeAfter($before, $after),
            new Children(
                new ComponentCollection([
                    new Component(
                        __('Before', 'codepress-admin-columns'),
                        null,
                        new Open(self::BEFORE, null, $before)
                    ),
                    new Component(
                        __('After', 'codepress-admin-columns'),
                        null,
                        new Open(self::AFTER, null, $after),
                    ),
                ])
            )
        );
    }
}