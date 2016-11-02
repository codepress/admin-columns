<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_Caption extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-caption';
		$this->properties['label'] = __( 'Caption', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return esc_html( $this->get_raw_value( $id ) );
	}

	public function get_raw_value( $id ) {
		return ac_helper()->post->get_raw_field( 'post_excerpt', $id );
	}

}