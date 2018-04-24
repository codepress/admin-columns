<?php

namespace AC\Admin\Addon;

use AC\Admin\Addon;

class BuddyPress extends Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-buddypress' );

		$this
			->set_title( __( 'BuddyPress', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_url() . 'assets/images/addons/buddypress.png' )
			->set_icon( AC()->get_url() . 'assets/images/addons/buddypress-icon.png' )
			->set_link( ac_get_site_utm_url( 'buddypress', 'addon', 'buddypress' ) )
			->set_description( __( 'Display any of your Profile Fields for BuddyPress on your users overview.', 'codepress-admin-columns' ) )
			->add_plugin( 'buddypress' );
	}

	public function display_promo() {
		echo $this->get_title() . ' ';
		$this->display_icon();
	}

	public function is_plugin_active() {
		return class_exists( 'BuddyPress', false );
	}

}