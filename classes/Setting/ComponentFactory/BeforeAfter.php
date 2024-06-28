<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\Open;

final class BeforeAfter implements ComponentFactory
{

    private const BEFORE = 'before';
    private const AFTER = 'after';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $before = $config->get(self::BEFORE);
        $after = $config->get(self::AFTER);

        return (new ComponentBuilder())
            ->set_label(
                __('Display Options', 'codepress-admin-columns')
            )
            ->set_formatter(
                new AC\Value\Formatter\BeforeAfter($before, $after)
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        new Component(
                            __('Prepend', 'codepress-admin-columns'),
                            __('Appears before the rendered column value', 'codepress-admin-columns'),
                            new Open(self::BEFORE, null, $before)
                        ),
                        new Component(
                            __('Append', 'codepress-admin-columns'),
                            __('Appears after the rendered column value', 'codepress-admin-columns'),
                            new Open(self::AFTER, null, $after)
                        ),
                    ])
                    , true
                )
            )
            ->build();
    }

}