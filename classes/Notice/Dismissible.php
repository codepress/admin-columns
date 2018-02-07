<?php

class AC_Notice_Dismissible extends AC_Notice {

	public function __construct( $message, $type = 'updated' ) {
		parent::__construct( $message, $type );

		$this->set_template( 'notice/dismissible' );
	}

	public function scripts() {
		parent::scripts();

		// TODO: dismiss logic
	}

}