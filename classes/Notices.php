<?php

class AC_Notices {

	/**
	 * @var AC_Notice[]
	 */
	private $notices = array();

	public function __construct() {
		add_action( 'admin_init', array( $this, 'hooks' ), 20 ); // make sure to register notices on `admin_init` before prio 20
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		add_action( 'admin_notices', array( $this, 'display' ) );
		add_action( 'network_admin_notices', array( $this, 'display' ) );
	}

	public function hooks() {
		foreach ( $this->notices as $notice ) {
			$notice->register();
		}
	}

	public function scripts() {
		foreach ( $this->notices as $notice ) {
			$notice->scripts();
		}
	}

	/**
	 * Display admin notice
	 */
	public function display() {
		$messages = array();

		foreach ( $this->notices as $notice ) {
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