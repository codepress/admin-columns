<?php

namespace AC\Helper;

use WP_Taxonomy;
use WP_Term;

class Taxonomy
{

    private Html $html;

    public function __construct()
    {
        $this->html = new Html();
    }

    public function get_term_links(array $terms, string $post_type = null): array
    {
        $values = [];

        foreach ($terms as $term) {
            if ( ! $term instanceof WP_Term) {
                continue;
            }

            $values[] = $this->html->link(
                $this->get_filter_by_term_url($term, $post_type),
                sanitize_term_field('name', $term->name, $term->term_id, $term->taxonomy, 'display')
            );
        }

        return $values;
    }

    public function get_filter_by_term_url(WP_Term $term, string $post_type = null): string
    {
        $args = [
            'taxonomy' => $term->taxonomy,
            'term'     => $term->slug,
        ];

        if ($post_type) {
            $args['post_type'] = $post_type;
        }

        $page = 'attachment' === $post_type
            ? 'upload.php'
            : 'edit.php';

        return add_query_arg(
            $args,
            $page
        );
    }

    public function get_term_display_name(WP_Term $term): string
    {
        return (string)sanitize_term_field('name', $term->name, $term->term_id, $term->taxonomy, 'display');
    }

    public function get_term_field(string $field, int $term_id, string $taxonomy): ?string
    {
        $term = get_term_by('id', $term_id, $taxonomy);

        if ( ! $term instanceof WP_Term) {
            return null;
        }

        if ( ! isset($term->{$field})) {
            return null;
        }

        return (string)$term->{$field};
    }

    public function get_taxonomy_selection_options($post_type): array
    {
        $taxonomies = get_object_taxonomies($post_type, 'objects');

        $options = [];

        foreach ($taxonomies as $index => $taxonomy) {
            if ($taxonomy->name == 'post_format') {
                unset($taxonomies[$index]);
            }
            $options[$taxonomy->name] = $taxonomy->label;
        }

        natcasesort($options);

        return $options;
    }

    public function get_terms_by_ids(array $term_ids, string $taxonomy): array
    {
        $terms = [];

        foreach ($term_ids as $term_id) {
            $term = get_term((int)$term_id, $taxonomy);

            if ($term instanceof WP_Term) {
                $terms[] = $term;
            }
        }

        return $terms;
    }

    public function get_taxonomy_label(string $taxonomy, string $property = 'name'): string
    {
        $taxonomy_object = get_taxonomy($taxonomy);

        if ( ! $taxonomy_object instanceof WP_Taxonomy) {
            return $taxonomy;
        }

        return get_taxonomy_labels($taxonomy_object)->$property ?? $taxonomy;
    }

}