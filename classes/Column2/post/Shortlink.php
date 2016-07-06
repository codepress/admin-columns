<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Post_Shortlink extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-shortlink';
		$this->properties['label'] = __( 'Shortlink', 'codepress-admin-columns' );
	}

	function get_value( $post_id ) {
		$link = $this->get_raw_value( $post_id );

		return '<a href="' . esc_attr( $link ) . '">' . esc_html( $link ) . '</a>';
	}

	function get_raw_value( $post_id ) {
		return wp_get_shortlink( $post_id );
	}

}