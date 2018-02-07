<?php

class AC_Notices {

	/**
	 * @var AC_Notice[]
	 */
	private $notices = array();

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );

		add_action( 'admin_notices', array( $this, 'display' ) );
		add_action( 'network_admin_notices', array( $this, 'display' ) );

		add_action( 'wp_ajax_ac_notices', array( $this, 'ajax_notices' ) );
	}

	/**
	 * @param AC_Notice $notice
	 */
	public function register( $notice ) {
		$this->notices[] = $notice;
	}

	/**
	 * Enqueue scripts and styles
	 */
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

		echo implode( array_unique( array_filter( $messages ) ) );
	}

	/**
	 * Hide notice
	 */
	public function ajax_notices() {
		check_ajax_referer( 'ac-ajax' );

		foreach ( $this->notices as $notice ) {
			if ( $notice instanceof AC_Notice_Updatable ) {
				$notice->update();
			}
		}

		exit;
	}

}