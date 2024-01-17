<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 */
class Taxonomy extends Column
{

    public function __construct()
    {
        $this->set_type('column-taxonomy');
        $this->set_label(__('Taxonomy', 'codepress-admin-columns'));
    }

    public function get_taxonomy()
    {
        return $this->get_option('taxonomy') ?? '';
    }

    public function get_value($post_id)
    {
        $_terms = $this->get_raw_value($post_id);

        if (empty($_terms)) {
            return $this->get_empty_char();
        }

        $terms = [];

        // TODO Stefan check formatted value original value. Can be ID or link?
        foreach ($_terms as $term) {
            $terms[] = $this->get_formatted_value($term->name, $term->term_id);
        }

        if (empty($terms)) {
            return $this->get_empty_char();
        }

        return ac_helper()->html->more(
            $terms,
            $this->get_items_limit(),
            $this->get_separator()
        );
    }

    private function get_items_limit(): int
    {
        return (int)$this->get_option('number_of_items') ?: 0;
    }

    /**
     * @param int $post_id
     *
     * @return array|false
     */
    public function get_raw_value($post_id)
    {
        $terms = get_the_terms($post_id, $this->get_taxonomy());

        if ( ! $terms || is_wp_error($terms)) {
            return false;
        }

        return $terms;
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\Taxonomy());
        $this->add_setting(new Settings\Column\TermLink());
        $this->add_setting(new Settings\Column\NumberOfItems());
        $this->add_setting(new Settings\Column\Separator());
    }

}