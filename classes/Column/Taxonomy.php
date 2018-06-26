<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
 *
 * @since 2.0
 */
class Taxonomy extends Column {

	public function __construct() {
		$this->set_type( 'column-taxonomy' );
		$this->set_label( __( 'Taxonomy', 'codepress-admin-columns' ) );
	}

	public function get_taxonomy() {
		return $this->get_option( 'taxonomy' );
	}

	public function get_value( $post_id ) {
		$terms = ac_helper()->taxonomy->get_term_links( $this->get_raw_value( $post_id ), get_post_type( $post_id ) );

		if ( empty( $terms ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->string->enumeration_list( $terms, 'and' );
	}

	/**
	 * @param int $post_id
	 *
	 * @return array|false
	 */
	public function get_raw_value( $post_id ) {
		$terms = get_the_terms( $post_id, $this->get_taxonomy() );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return false;
		}

		return $terms;
	}

	public function register_settings() {
		$this->add_setting( new Settings\Column\Taxonomy( $this ) );
	}

}