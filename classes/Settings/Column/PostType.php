<?php

namespace AC\Settings\Column;

use AC\Setting;
use AC\Setting\OptionCollection;
use AC\Settings;

class PostType extends Settings\Column
{

    private $show_any;

    public function __construct(bool $show_any = false)
    {
        $input = Setting\Input\Option\Single::create_select(
            $this->create_options()
        );

        parent::__construct(
            'post_type',
            __('Post Type', 'codepress-admin-columns'),
            null,
            $input
        );

        $this->show_any = $show_any;
    }

    public function create_options(): OptionCollection
    {
        $options = $this->get_post_type_labels();

        if ($this->show_any) {
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
            $options[$post_type] = $post_type_object->labels->name;
        }

        $options = $this->add_slug_to_duplicate_post_type_label($options);

        natcasesort($options);

        return $options;
    }

}