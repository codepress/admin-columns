<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class PostStatus implements ComponentFactory
{

    private const NAME = 'post_status';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = $config->has(self::NAME) ? $config->get(self::NAME) : null;

        $builder = (new ComponentBuilder())
            ->set_label(__('Post Status', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    $this->create_options(),
                    $value ?: ['publish', 'private']
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function create_options(): OptionCollection
    {
        $options = [];

        // TODO test
        foreach (get_post_stati(['exclude_from_search' => false]) as $name) {
            $status = get_post_status_object((string)$name);
            $options[$name] = $status->label ?? (string)$name;
        }

        return OptionCollection::from_array($options);
    }

}