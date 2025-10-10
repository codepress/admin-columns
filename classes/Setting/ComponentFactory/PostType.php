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

final class PostType implements ComponentFactory
{

    private bool $show_any;

    public function __construct(bool $show_any = true)
    {
        $this->show_any = $show_any;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $post_type_options = $this->create_options($this->show_any);
        $post_type = $config->has('post_type')
            ? $config->get('post_type')
            : $post_type_options->first()->get_value();

        $builder = (new ComponentBuilder())
            ->set_label(__('Post Type', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'post_type',
                    $post_type_options,
                    $post_type
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    private function create_options($show_any): OptionCollection
    {
        $options = $this->get_post_type_labels();

        if ($show_any) {
            $options = ['any' => __('All Post Types', 'codepress-admin-columns')] + $options;
        }

        return OptionCollection::from_array($options);
    }

    private function add_slug_to_duplicate_post_type_label(array $options): array
    {
        $values = array_values($options);

        // Add slug to duplicate post type labels
        foreach ($options as $k => $label) {
            if (count(array_keys($values, $label)) > 1) {
                $options[$k] .= sprintf(' (%s)', $k);
            }
        }

        return $options;
    }

    private function get_post_type_labels(): array
    {
        $options = [];

        $post_types = get_post_types();

        if ( ! is_array($post_types)) {
            return $options;
        }

        foreach ($post_types as $post_type) {
            $post_type_object = get_post_type_object($post_type);
            
            if ($post_type_object) {
                $options[$post_type_object->name] = $post_type_object->labels->name;
            }
        }

        $options = $this->add_slug_to_duplicate_post_type_label($options);

        natcasesort($options);

        return $options;
    }

}