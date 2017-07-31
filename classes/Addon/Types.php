<?php

class AC_Addon_Types extends AC_Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-types' );

		$this
			->set_title( __( 'Toolset Types', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/toolset-types.png' )
			->set_icon( AC()->get_plugin_url() . 'assets/images/addons/toolset-types-icon.png' )
			->set_link( ac_get_site_utm_url( 'toolset-types', 'addon', 'types' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->add_plugin( 'types' );
	}

	public function show_missing_notice_on_current_page() {
		global $pagenow;

		$is_page = 'admin.php' === $pagenow && in_array( filter_input( INPUT_GET, 'page' ), array( 'toolset-dashboard', 'wpcf-cpt', 'wpcf-cf', 'toolset-settings' ) );

		return parent::show_missing_notice_on_current_page() || $is_page;
	}

	public function is_plugin_active() {
		return class_exists( 'Types_Main', false );
	}

}