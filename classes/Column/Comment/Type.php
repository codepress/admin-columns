<?php

namespace AC\Column\Comment;

use AC\Column;

/**
 * @since 2.4.2
 */
class Type extends Column {

	public function __construct() {
		$this->set_type( 'column-type' )
		     ->set_label( __( 'Type', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$type = $this->get_raw_value( $id );

		if ( ! $type ) {
			return $this->get_empty_char();
		}

		return $type;
	}

	public function get_raw_value( $id ) {
		return get_comment( $id )->comment_type;
	}

}