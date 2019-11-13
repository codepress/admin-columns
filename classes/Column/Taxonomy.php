<?php

namespace AC\Column;

use AC\Collection;
use AC\Column;
use AC\Settings;

/**
 * Taxonomy column, displaying terms from a taxonomy for any object type (i.e. posts)
 * supporting WordPress' native way of handling terms.
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
		$_terms = $this->get_raw_value( $post_id );
		if ( empty( $_terms ) ) {
			return $this->get_empty_char();
		}

		$collection = new Collection( $_terms );
		$terms = [];

		foreach ( $collection as $term ) {
			$terms[] = $this->get_formatted_value( $term->name, $term );
		}

		if ( empty( $terms ) ) {
			return $this->get_empty_char();
		}

		$setting_limit = $this->get_setting( 'number_of_items' );

		return ac_helper()->html->more( $terms, $setting_limit ? $setting_limit->get_value() : false );
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
		$this->add_setting( new Settings\Column\TermLink( $this ) );
		$this->add_setting( new Settings\Column\NumberOfItems( $this ) );
	}

}