<?php

namespace AC\Column\Post;

use AC\Column;

class PageTemplate extends Column\Meta
{

    public function __construct()
    {
        $this->set_type('column-page_template');
        $this->set_label(__('Page Template', 'codepress-admin-columns'));
    }

    public function get_meta_key()
    {
        return '_wp_page_template';
    }

    function get_value($post_id)
    {
        $template = array_search($this->get_raw_value($post_id), $this->get_page_templates());

        if ( ! $template) {
            return $this->get_empty_char();
        }

        return $template;
    }

    public function get_page_templates(): array
    {
        global $wp_version;

        if ( ! function_exists('get_page_templates')) {
            return [];
        }

        if (version_compare($wp_version, '4.7', '>=')) {
            return get_page_templates(null, $this->get_post_type());
        }

        return get_page_templates();
    }

}