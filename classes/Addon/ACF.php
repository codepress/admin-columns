<?php

class AC_Addon_ACF extends AC_Addon {

	public function __construct() {
		$this
			->set_title( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) )
			->set_description( __( 'Display and edit Advanced Custom Fields fields in the posts overview in seconds!', 'codepress-admin-columns' ) )
			->set_slug( 'cac-addon-acf' )
			->set_image_url( AC()->get_plugin_url() . 'assets/images/addons/acf.png' )
			->set_link( ac_get_site_utm_url( 'advanced-custom-fields-columns', 'addon' ) );
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function is_addon_active() {
		return function_exists( 'ac_addon_acf' );
	}

	public function get_placeholder_column() {
		$column = new AC_Column_Placeholder();
		$column->set_addon( $this );

		return $column;
	}

}