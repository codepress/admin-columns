<?php

namespace AC\ThirdParty;

use AC\ListScreenRepository\Storage;
use AC\Registerable;

/**
 * WPML compatibility
 */
class WPML implements Registerable
{

    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function register(): void
    {
        // Display correct flags on the list tables
        add_action('ac/table/list_screen', [$this, 'replace_flags']);

        // Enable the translation of the column labels
        add_action('init', [$this, 'register_column_labels'], 300);

        // Enable the WPML translation of column headings
        add_filter('ac/column/heading/label', [$this, 'register_translated_label'], 100);
    }

    public function replace_flags()
    {
        if ( ! class_exists('SitePress', false)) {
            return;
        }

        $settings = get_option('icl_sitepress_settings');

        $post_types = $settings['custom_posts_sync_option'] ?? [];

        if ( ! $post_types) {
            return;
        }

        $post_types = (array)$post_types;
        $post_types['post'] = 1;
        $post_types['page'] = 1;

        foreach ($post_types as $post_type => $value) {
            if ($value) {
                new WPMLColumn((string)$post_type);
            }
        }
    }

    private function is_translation_page(): bool
    {
        $page = $_GET['page'] ?? null;

        return 'wpml-string-translation/menu/string-translation.php' === $page;
    }

    // Create translatable column labels
    public function register_column_labels(): void
    {
        if ( ! $this->is_translation_page()) {
            return;
        }

        foreach ($this->storage->find_all() as $list_screen) {
            foreach ($list_screen->get_columns() as $column) {
                $label = $column->get_setting('label')->get_input()->set_value($column->get_label());

                do_action(
                    'wpml_register_single_string',
                    'Admin Columns',
                    $label,
                    $label
                );
            }
        }
    }

    public function register_translated_label($label)
    {
        if (defined('ICL_LANGUAGE_CODE')) {
            $label = apply_filters('wpml_translate_single_string', $label, 'Admin Columns', $label, ICL_LANGUAGE_CODE);
        }

        return $label;
    }

}