<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\OptionCollection;

final class IncludeMissingSizes extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Include missing sizes?', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Include sizes that are missing an image file.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return Input\OptionFactory::create_toggle(
            'include_missing_sizes',
            OptionCollection::from_array([
                '1',
                '',
            ], false),
            (string)$config->get('include_missing_sizes')
        );
    }

}