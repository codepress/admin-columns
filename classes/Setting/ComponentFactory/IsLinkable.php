<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Formatter\Linkable;
use AC\FormatterCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;

class IsLinkable extends BaseComponentFactory
{

    protected function get_label(Config $config): ?string
    {
        return __('Show Link', 'codepress-admin-columns');
    }

    protected function get_description(Config $config): ?string
    {
        return __('This will make the value linkable if it is an URL.', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_toggle('is_linkable', null, 'on' === $config->get('is_linkable') ? 'on' : 'off');
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        if ($config->get('is_linkable') === 'on') {
            $formatters->add(new Linkable());
        }
        $formatters->add(new Linkable());
    }

}