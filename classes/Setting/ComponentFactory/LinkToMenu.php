<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Formatter\MenuLink;

class LinkToMenu implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $link_to_menu = 'on' === $config->get('link_to_menu');

        $builder = (new ComponentBuilder());
        $builder
            ->set_input(
                OptionFactory::create_toggle(
                    'link_to_menu',
                    null,
                    $link_to_menu ? 'on' : 'off'
                )
            )
            ->set_label(__('Link to menu', 'codepress-admin-columns'))
            ->set_description(__('This will make the title link to the menu.', 'codepress-admin-columns'));

        if ($link_to_menu) {
            $builder->set_formatter(new MenuLink());
        }

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}