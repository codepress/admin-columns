<?php

namespace AC\Column\Media;

use AC\Column;

/**
 * @since 2.0
 */
class Caption extends Column {

	public function __construct() {
		$this->set_type( 'column-caption' )
		     ->set_group( 'custom' )
		     ->set_label( __( 'Caption', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = esc_html( $this->get_raw_value( $id ) );

		if ( ! $value ) {
			return $this->get_empty_char();
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return ac_helper()->post->get_raw_field( 'post_excerpt', $id );
	}

}