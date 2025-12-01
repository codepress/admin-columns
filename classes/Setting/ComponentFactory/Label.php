<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Sanitize\Kses;
use AC\Setting\Config;
use AC\Setting\Control\Input;

final class Label extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Label', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This is the name which will appear as the column header.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        $label = $config->has('label')
            ? (string)$config->get('label')
            : '';

        $label = (string)apply_filters(
            'ac/column/label',
            (new Kses())->sanitize($label),
            $label
        );

        return new Input\Custom(
            'label',
            'label',
            [],
            $label
        );
    }

}