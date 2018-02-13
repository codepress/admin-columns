<?php

class AC_Admin_Addon_ACF extends AC_Admin_Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-acf' );

		$this
			->set_title( __( 'Advanced Custom Fields', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/acf.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'advanced-custom-fields-columns', 'addon', 'acf' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->add_plugin( 'advanced-custom-fields' )
			->add_plugin( 'advanced-custom-fields-pro' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

	public function get_plugin() {
		foreach ( $this->get_plugins() as $plugin ) {
			if ( $plugin->is_installed() ) {
				return $plugin;
			}
		}

		return parent::get_plugin();
	}

	public function is_plugin_active() {
		return class_exists( 'acf', false );
	}

	public function show_missing_notice_on_current_page() {
		global $pagenow;

		$is_acf_page = 'edit.php' === $pagenow && 'acf-field-group' === filter_input( INPUT_GET, 'post_type' );

		return parent::show_missing_notice_on_current_page() || $is_acf_page;
	}

}