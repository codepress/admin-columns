<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class PostStatusIcon extends Builder
{

    protected function get_label(Config $config): ?string
    {
        return __('Use an icon?', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('Use an icon instead of text for displaying the status.', 'codepress-admin-');
    }

    protected function get_input(Config $config): ?Input
    {
        $value = (string)$config->get('use_icon') === 'on';

        return OptionFactory::create_toggle(
            'use_icon',
            (new ToggleOptionCollection())->create(),
            $value ? 'on' : 'off'
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        if ((string)$config->get('use_icon') === 'on') {
            $formatters->add(new Formatter\Post\PostStatusIcon());
        }

        if ((string)$config->get('use_icon') === 'off') {
            $formatters->add(new Formatter\Post\DescriptivePostStatus());
        }

        return parent::get_formatters($config, $formatters);
    }

}