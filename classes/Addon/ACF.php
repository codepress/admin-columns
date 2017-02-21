<?php

class AC_Addon_ACF extends AC_Addon {

	public function __construct() {
		$this
			->set_title( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) )
			->set_slug( 'cac-addon-acf' )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/acf.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'advanced-custom-fields-columns', 'addon', 'acf' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) );
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

}