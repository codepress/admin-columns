<?php

class AC_Addon_Pods extends AC_Addon {

	public function __construct() {
		parent::__construct( 'ac-addon-pods' );

		$this
			->set_title( __( 'Pods', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/pods.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'pods-columns', 'addon', 'pods' ) )
			->set_description( $this->get_fields_description( $this->get_title() ) )
			->set_plugin( 'pods' );
	}

}