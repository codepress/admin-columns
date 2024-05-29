<?php

namespace AC\Column\Post;

use AC\Column;

class Shortcodes extends Column
{

    public function __construct()
    {
        $this->set_type('column-shortcode');
        $this->set_label(__('Shortcodes', 'codepress-admin-columns'));
    }

    public function get_value($post_id)
    {
        $shortcodes = $this->get_raw_value($post_id);

        if ( ! $shortcodes) {
            return $this->get_empty_char();
        }

        $display = [];
        foreach ($shortcodes as $sc => $count) {
            $string = '[' . $sc . ']';

            if ($count > 1) {
                $string .= ac_helper()->html->rounded((string)$count);
            }

            $display[$sc] = '<span class="ac-spacing">' . $string . '</span>';
        }

        return implode(' ', $display);
    }

    public function get_raw_value($post_id)
    {
        global $shortcode_tags;

        if ( ! $shortcode_tags) {
            return false;
        }

        return ac_helper()->string->get_shortcodes(get_post_field('post_content', $post_id));
    }

}