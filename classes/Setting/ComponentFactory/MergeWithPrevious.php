<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Open;
use AC\Setting\Control\Input\OptionFactory;

final class MergeWithPrevious extends BaseComponentFactory
{
    public const MERGE = 'merge_with_previous';
    public const SEPARATOR = 'merge_separator';

    protected function get_label(Config $config): ?string
    {
        return __('Merge with previous column', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __(
            'Show this value inside the cell of the column above, instead of in a column of its own.',
            'codepress-admin-columns'
        );
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle(
            self::MERGE,
            null,
            'on' === $config->get(self::MERGE) ? 'on' : 'off'
        );
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                new Component(
                    __('Separator', 'codepress-admin-columns'),
                    null,
                    new Open(self::SEPARATOR, 'text', $config->get(self::SEPARATOR, ' ')),
                    StringComparisonSpecification::equal('on')
                ),
            ]),
            true
        );
    }

}
