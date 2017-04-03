<?php

/**
 * @since 2.0
 */
class AC_Column_Media_AttachedTo extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-attached_to' );
		$this->set_label( __( 'Attached to Post', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$value = false;

		if ( $attached_to = $this->get_raw_value( $id ) ) {
			$value = ac_helper()->html->link( get_edit_post_link( $id ), get_the_title( $attached_to ) );
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return ac_helper()->post->get_raw_field( 'post_parent', $id );
	}

}