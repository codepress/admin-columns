<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter\Aggregate;

class TermProperty implements ComponentFactory
{

    private const NAME = 'term_property';

    //TODO implement formatter
    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Term Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array(
                        [
                            ''     => __('Title'),
                            'slug' => __('Slug'),
                            'id'   => __('ID'),
                        ]
                    ),
                    (string)$config->get(self::NAME)
                )
            )
            ->set_formatter(
                new Aggregate([
                    
                ])
            );
    }

}