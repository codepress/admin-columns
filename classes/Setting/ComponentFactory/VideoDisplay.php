<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

class VideoDisplay implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'video_display',
                    OptionCollection::from_array([
                        'embed' => __('Embed', 'codepress-admin-columns'),
                        'modal' => __('Pop Up', 'codepress-admin-columns'),
                    ]),
                    $config->get('video_display') ?: 'embed'
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}