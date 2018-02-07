<?php

class AC_Notice extends AC_View {

	/**
	 * AC_Notice constructor.
	 *
	 * @param string $message
	 * @param string $type
	 */
	public function __construct( $message, $type = 'updated' ) {
		parent::__construct( array(
			'message' => $message,
			'type'    => $type,
		) );

		$this->set_template( 'notice/message' );
	}

	/**
	 * Enqueue scripts & styles
	 */
	public function scripts() {
		wp_enqueue_style( 'ac-message', AC()->get_plugin_url() . "assets/css/message.css", array(), AC()->get_version() );
	}

}