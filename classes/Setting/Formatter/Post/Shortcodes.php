<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Shortcodes implements Formatter
{

    public function format(Value $value): Value
    {
        $shortcodes = $this->get_used_shortcodes($value->get_id());

        if ( ! $shortcodes) {
            return $value->with_value(false);
        }

        $display = [];

        foreach ($shortcodes as $sc => $count) {
            $string = '[' . $sc . ']';

            if ($count > 1) {
                $string .= ac_helper()->html->rounded($count);
            }

            $display[$sc] = '<span class="ac-spacing">' . $string . '</span>';
        }

        return $value->with_value(implode(' ', $display));
    }

    private function get_used_shortcodes($post_id): ?array
    {
        global $shortcode_tags;

        return $shortcode_tags
            ? ac_helper()->string->get_shortcodes(get_post_field('post_content', $post_id))
            : null;
    }

}