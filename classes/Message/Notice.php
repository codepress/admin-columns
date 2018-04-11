<?php

class AC_Message_Notice extends AC_Message {

	public function create_view() {
		$data = array(
			'message' => $this->message,
			'type'    => $this->type,
			'id'      => $this->id,
		);

		$view = new AC_View( $data );
		$view->set_template( 'message/notice' );

		return $view;
	}

	public function register() {
		if ( apply_filters( 'ac/suppress_site_wide_notices', false ) ) {
			return;
		}

		add_action( 'admin_notices', array( $this, 'display' ) );
		add_action( 'network_admin_notices', array( $this, 'display' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . 'assets/css/notice.css', array(), AC()->get_version() );
	}

}