<?php

namespace AC\Column\Post;

use AC\Column;

class PageTemplate extends Column
{

    public function __construct()
    {
        $this->set_type('column-page_template');
        $this->set_label(__('Page Template', 'codepress-admin-columns'));
    }

    public function get_value($id)
    {
        $template = get_post_meta($id, '_wp_page_template', true);
        $template = array_search($template, $this->get_page_templates());

        if ( ! $template) {
            return $this->get_empty_char();
        }

        return $template;
    }

    public function get_page_templates(): array
    {
        return get_page_templates(null, $this->get_post_type());
    }

}