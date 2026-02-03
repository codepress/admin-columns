<?php

namespace AC\Helper;

use WP_Theme;

class Network
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

    public function get_active_theme(int $blog_id): WP_Theme
    {
        return wp_get_theme($this->get_site_option($blog_id, 'stylesheet'));
    }

}