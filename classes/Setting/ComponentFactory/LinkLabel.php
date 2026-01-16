<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OpenFactory;
use AC\Value\Formatter;

final class LinkLabel implements ComponentFactory
{

    private const NAME = 'link_label';

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME);

        $builder = (new ComponentBuilder())
            ->set_label(__('Link Label', 'codepress-admin-columns'))
            ->set_description(__('Leave blank to display the URL', 'codepress-admin-columns'))
            ->set_input(OpenFactory::create_text(self::NAME, $value))
            ->set_formatter(
                new \AC\Formatter\Linkable($value)
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}