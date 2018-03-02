<?php

class AC_Admin_Addon_Pods extends AC_Admin_Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-pods' );

		$this
			->set_title( __( 'Pods', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/pods.png' )
			->set_icon( AC()->get_plugin_url() . 'assets/images/addons/pods-icon.png' )
			->set_link( ac_get_site_utm_url( 'pods', 'addon', 'pods' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->add_plugin( 'pods' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

	public function is_notice_screen() {
		global $pagenow;

		return 'admin.php' === $pagenow && in_array( filter_input( INPUT_GET, 'page' ), array( 'pods-add-new', 'pods-settings' ) );
	}

	public function is_plugin_active() {
		return function_exists( 'pods' );
	}

}