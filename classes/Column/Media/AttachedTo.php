<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_AttachedTo extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-attached_to';
		$this->properties['label'] = __( 'Attached to post', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$value = false;
		if ( $attached_to = $this->get_raw_value( $id ) ) {
			$value = get_the_title( $attached_to );
			if ( $edit_link = get_edit_post_link( $id ) ) {
				$value = '<a href="' . $edit_link . '">' . $value . '</a>';
			}
		}

		return $value;
	}

	public function get_raw_value( $id ) {
		return get_post_field( $id, 'post_parent' );
	}

}