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

    private function use_icon(Config $config): bool
    {
        return (string)$config->get('use_icon') === 'on';
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle(
            'use_icon',
            (new ToggleOptionCollection())->create(),
            $this->use_icon($config) ? 'on' : 'off'
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $this->use_icon($config)
            ? $formatters->add(new Formatter\Post\PostStatusIcon())
            : $formatters->add(new Formatter\Post\DescriptivePostStatus());
    }

}