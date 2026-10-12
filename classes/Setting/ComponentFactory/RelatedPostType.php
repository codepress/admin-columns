<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\AttributeCollection;
use AC\Setting\AttributeFactory;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

/**
 * Selects the post type of the posts that reference the row's post.
 * Changing it refreshes the column settings so the meta key select can reload its options.
 */
class RelatedPostType implements ComponentFactory
{
    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $options = $this->get_post_types();
        $first_option = $options->first();

        $post_type = $config->has('related_post_type')
            ? $config->get('related_post_type')
            : ($first_option ? $first_option->get_value() : null);

        $builder = (new ComponentBuilder())
            ->set_label(__('Related Post Type', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'related_post_type',
                    $options,
                    $post_type,
                    null,
                    false,
                    new AttributeCollection([
                        AttributeFactory::create_refresh(),
                    ])
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function get_post_types(): OptionCollection
    {
        $options = [];

        foreach (get_post_types(['show_ui' => true], 'objects') as $post_type) {
            $options[$post_type->name] = $post_type->labels->name;
        }

        natcasesort($options);

        return OptionCollection::from_array($options);
    }

}
