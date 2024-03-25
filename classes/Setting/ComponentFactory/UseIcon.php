<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Formatter\Post\PostFormatIcon;
use AC\Setting\FormatterCollection;

class UseIcon extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Use an icon?', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Use an icon instead of text for displaying.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle('use_icon', null, 'on' === $config->get('use_icon') ? 'on' : 'off');
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        if ($config->get('use_icon') === 'on') {
            $formatters->add(new PostFormatIcon());
        }

        return $formatters;
    }
}