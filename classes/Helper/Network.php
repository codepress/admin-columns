<?php

declare(strict_types=1);

namespace AC\Helper;

use WP_Theme;

class Network extends Creatable
{
    public function get_site_option(int $blog_id, string $option): string
    {
        global $wpdb;

        $table = $wpdb->get_blog_prefix($blog_id) . 'options';

        $sql = "
			SELECT $table.option_value 
			FROM $table
			WHERE option_name = %s
		";

        return (string)$wpdb->get_var($wpdb->prepare($sql, $option));
    }

    public function get_active_theme(int $blog_id): ?WP_Theme
    {
        $stylesheet = $this->get_site_option($blog_id, 'stylesheet');

        // An empty stylesheet would make wp_get_theme() fall back to the current site's theme, so bail instead.
        if ('' === $stylesheet) {
            return null;
        }

        return wp_get_theme($stylesheet);
    }

}
