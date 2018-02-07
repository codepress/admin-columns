<?php

class AC_Notices {

	/**
	 * @var AC_Notice[]
	 */
	private $notices;

	public function __construct() {
		add_action( 'admin_notices', array( $this, 'display' ) );
		add_action( 'network_admin_notices', array( $this, 'display' ) );
	}

	/**
	 * Display admin notice
	 */
	public function display() {
		$messages = array();

		foreach ( $this->notices as $notice ) {

			// Load scripts & styles
			$notice->scripts();

			$messages[] = $notice->render();
		}

		if ( $messages ) {
			echo implode( array_unique( $messages ) );
		}
	}

	/**
	 * @param AC_Notice $notice
	 */
	public function register( $notice ) {
		$this->notices[] = $notice;
	}

}