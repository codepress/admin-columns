<?php

namespace AC\Admin\Addon;

use AC\Admin\Addon;

class Types extends Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-types' );

		$this
			->set_title( __( 'Toolset Types', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_url() . 'assets/images/addons/toolset-types.png' )
			->set_icon( AC()->get_url() . 'assets/images/addons/toolset-types-icon.png' )
			->set_link( ac_get_site_utm_url( 'toolset-types', 'addon', 'types' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->add_plugin( 'types' );
	}

	public function is_notice_screen() {
		global $pagenow;

		return 'admin.php' === $pagenow && in_array( filter_input( INPUT_GET, 'page' ), array( 'toolset-dashboard', 'wpcf-cpt', 'wpcf-cf', 'toolset-settings' ) );
	}

	public function is_plugin_active() {
		return class_exists( 'Types_Main', false );
	}

}