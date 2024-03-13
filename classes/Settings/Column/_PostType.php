<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting;
use AC\Setting\Control\Input\OptionFactory;
use AC\Settings;

class PostType extends Settings\Control
{

    private $show_any;

    public function __construct(string $post_type = null, bool $show_any = false, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_select('post_type', $this->create_options($show_any), $post_type ?: 'any'),
            __('Post Type', 'codepress-admin-columns'),
            null,
            $specification
        );
    }

    public function create_options($show_any): Setting\Control\OptionCollection
    {
        $options = $this->get_post_type_labels();

        if ($show_any) {
            $options = ['any' => __('All Post Types', 'codepress-admin-columns')] + $options;
        }

        return Setting\Control\OptionCollection::from_array($options);
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
                $options[$post_type] = $post_type_object->labels->name;
            }
        }

        $options = $this->add_slug_to_duplicate_post_type_label($options);

        natcasesort($options);

        return $options;
    }

}