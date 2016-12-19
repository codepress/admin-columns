<?php

class AC_Addon_ACF extends AC_Addon {

	public function __construct() {
		$this
			->set_title( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) )
			->set_description( __( 'Display and edit Advanced Custom Fields fields in the posts overview in seconds!', 'codepress-admin-columns' ) )
			->set_slug( 'cac-addon-acf' )
			->set_image_url( AC()->get_plugin_url() . 'assets/images/addons/acf.png' );
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function is_addon_active() {
		return class_exists( 'CPAC_Addon_ACF', false );
	}

}