<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class PathScope implements ComponentFactory
{

    private const NAME = 'path_scope';

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $value = $config->get(self::NAME) ?: 'full';

        $builder = (new ComponentBuilder())
            ->set_label(__('Path scope', 'codepress-admin-columns'))
            ->set_description(__('Part of the file path to display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array([
                        'full'             => __('Full Path', 'codepress-admin-columns'),
                        'relative-domain'  => __('Relative to domain', 'codepress-admin-columns'),
                        'relative-uploads' => __('Relative to main uploads folder', 'codepress-admin-columns'),
                        'local'            => __('Local Path', 'codepress-admin-columns'),
                    ]),
                    $value
                )
            )
            ->set_formatter(new AC\Formatter\PathScope($value));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}