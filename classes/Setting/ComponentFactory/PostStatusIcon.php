<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollectionFactory\ToggleOptionCollection;
use AC\Setting\Formatter;

class PostStatusIcon implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get('user_icon') === '1';

        $builder = (new ComponentBuilder())
            ->set_label(__('Use an icon?', 'codepress-admin-columns'))
            ->set_description(__('Use an icon instead of text for displaying the status.', 'codepress-admin-'))
            ->set_input(
                OptionFactory::create_toggle(
                    'user_icon',
                    (new ToggleOptionCollection())->create(),
                    $value ? 'on' : 'off'
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    // TODO maybe even put the formatter out of the setting
    private function get_formatter(Config $config): Formatter
    {
        $use_icon = (string)$config->get('user_icon') === '1';

        if ($use_icon) {
            //TODO prepend with Post / Status formatter maybe both
            return new Formatter\Post\PostStatusIcon();
        }

        //TODO prepend with Post
        return new Formatter\Post\DescriptivePostStatus();
    }
}