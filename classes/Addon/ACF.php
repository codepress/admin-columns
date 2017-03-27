<?php

class AC_Addon_ACF extends AC_Addon {

	public function __construct() {
		parent::__construct( 'cac-addon-acf' );

		$this
			->set_title( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/acf.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'advanced-custom-fields-columns', 'addon', 'acf' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->set_plugin( 'advanced-custom-fields-pro' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

}