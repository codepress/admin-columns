<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying full item permalink (including URL).
 *
 * @since 2.0
 */
class AC_Column_Post_Permalink extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-permalink' );
		$this->set_label( __( 'Permalink', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_setting( 'link_to_post' )->format( $post_id );
	}

	public function get_raw_value( $post_id ) {
		return get_permalink( $post_id );
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_LinkToPost( $this ) );

	}

}