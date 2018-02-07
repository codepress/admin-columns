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
		if ( ! $this->notices ) {
			return;
		}

		$messages = array();

		foreach ( (array) $this->notices as $notice ) {

			// Load scripts & styles
			$notice->scripts();

			$messages[] = $notice->render();
		}

		echo implode( array_unique( $messages ) );
	}

	/**
	 * @param AC_Notice $notice
	 */
	public function register( $notice ) {
		$this->notices[] = $notice;
	}

}