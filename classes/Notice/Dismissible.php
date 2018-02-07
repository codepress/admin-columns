<?php

class AC_Notice_Dismissible extends AC_Notice {

	public function __construct( $message, $type = 'updated' ) {
		parent::__construct( $message, $type );

		$this->set_template( 'notice/dismissible' );
	}

	public function scripts() {
		parent::scripts();

		// TODO register one, enqueue when necessary
		wp_enqueue_script( 'ac-message', AC()->get_plugin_url() . "assets/js/message.js", array(), AC()->get_version(), true );

		// TODO: dismiss logic
	}

}