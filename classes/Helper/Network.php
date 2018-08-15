<?php

namespace AC\Helper;

class Network {

	/**
	 * @param int    $blog_id
	 * @param string $option Option name
	 *
	 * @return null|string
	 */
	public function get_site_option( $blog_id, $option ) {
		global $wpdb;

		$table = $wpdb->get_blog_prefix( $blog_id ) . 'options';

		$sql = "
			SELECT {$table}.option_value 
			FROM {$table}
			WHERE option_name = %s
		";

		return (string) $wpdb->get_var( $wpdb->prepare( $sql, $option ) );
	}

	/**
	 * @param int $blog_id
	 *
	 * @return \WP_Theme
	 */
	public function get_active_theme( $blog_id ) {
		return wp_get_theme( $this->get_site_option( $blog_id, 'stylesheet' ) );
	}

}