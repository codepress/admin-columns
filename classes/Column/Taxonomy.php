<?php

namespace AC\Column;

use AC\Column;
use AC\Settings;
use AC\Settings\Column\NumberOfItems;
use AC\Settings\Column\Separator;

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

		$terms = [];

		foreach ( $_terms as $term ) {
			$terms[] = $this->get_formatted_value( $term->name, $term );
		}

		if ( empty( $terms ) ) {
			return $this->get_empty_char();
		}

		return ac_helper()->html->more(
			$terms,
			$this->get_items_limit(),
			$this->get_separator()
		);
	}

	/**
	 * @return string
	 */
	public function get_separator() {
		$setting = $this->get_setting( Separator::NAME );

		return $setting instanceof Separator
			? $setting->get_separator_formatted()
			: parent::get_separator();
	}

	/**
	 * @return int
	 */
	private function get_items_limit() {
		$setting_limit = $this->get_setting( NumberOfItems::NAME );

		return $setting_limit instanceof NumberOfItems
			? $setting_limit->get_number_of_items()
			: 0;
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
		$this->add_setting( new Settings\Column\Separator( $this ) );
	}

}