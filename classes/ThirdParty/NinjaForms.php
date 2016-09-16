<?php
defined( 'ABSPATH' ) or die();

class AC_ThirdParty_NinjaForms {

	public function __construct() {
		add_filter( 'cac/post_types', array( $this, 'remove_ninja_forms_from_cpac_post_types' ) );
	}

	public function remove_ninja_forms_from_cpac_post_types( $post_types ) {
		if ( class_exists( 'Ninja_Forms', false ) ) {
			if ( isset( $post_types['nf_sub'] ) ) {
				unset( $post_types['nf_sub'] );
			}
		}

		return $post_types;
	}

}